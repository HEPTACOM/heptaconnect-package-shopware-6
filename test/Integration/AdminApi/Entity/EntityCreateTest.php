<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\NotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action\AbstractActionTestCase;
use Http\Discovery\Psr17FactoryDiscovery;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\NotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ResourceNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class EntityCreateTest extends AbstractActionTestCase
{
    public function testCreateTag(): void
    {
        $client = $this->createAction(EntityCreateAction::class);
        $name = \bin2hex(\random_bytes(24));
        $result = $client->create(new EntityCreatePayload('tag', [
            'name' => $name,
        ]));

        $get = $this->createAction(EntityGetAction::class);
        static::assertSame(
            $name,
            $get->get(
                new EntityGetCriteria($result->getEntityName(), $result->getId(), new Criteria())
            )->getEntity()->name
        );
    }

    public function testCreateTagWithPredefinedId(): void
    {
        $client = $this->createAction(EntityCreateAction::class);
        $id = \bin2hex(\random_bytes(16));
        $name = \bin2hex(\random_bytes(24));
        $result = $client->create(new EntityCreatePayload('tag', [
            'id' => $id,
            'name' => $name,
        ]));

        static::assertSame($id, $result->getId());
        static::assertSame('tag', $result->getEntityName());
    }

    public function testEntityFormatWithEntityThatContainsSeparator(): void
    {
        $client = $this->createAction(EntityCreateAction::class);
        $result = $client->create(new EntityCreatePayload('log-entry', [
            'message' => 'An test log message',
            'level' => 5,
            'channel' => 'phpunit',
            'context' => [],
            'extra' => [],
        ]));

        static::assertSame('log-entry', $result->getEntityName());
    }

    public function testEntityFormatWithWrongEntityNameSeparatorFails(): void
    {
        $client = $this->createAction(EntityCreateAction::class);

        static::expectException(NotFoundException::class);

        $client->create(new EntityCreatePayload('log_entry', []));
    }

    public function testCreatingAnEntityThatAlreadyExists(): void
    {
        $client = $this->createAction(EntityCreateAction::class);
        $defaultCurrencyId = 'b7d2554b0ce847cd82f3ac9bd1c0dfca';

        static::expectException(WriteTypeIntendException::class);

        $client->create(new EntityCreatePayload('currency', [
            'id' => $defaultCurrencyId,
            'iso' => 'foobar',
            'name' => 'foobar',
        ]));
    }

    public function testReceivingAnInvalidEntityReference(): void
    {
        $httpClient = $this->createMock(AuthenticatedHttpClientInterface::class);
        $httpClient->method('sendRequest')->willReturn(
            Psr17FactoryDiscovery::findResponseFactory()
                ->createResponse(204)
                ->withAddedHeader('location', 'http://127.0.0.1/')
        );
        $jsonStreamUtility = $this->createJsonStreamUtility();
        $client = new EntityCreateAction(
            $httpClient,
            $this->createRequestFactory(),
            $this->createApiConfigurationStorage(),
            $jsonStreamUtility,
            $this->createJsonResponseErrorHandler($jsonStreamUtility),
        );

        static::expectException(EntityReferenceLocationFormatInvalidException::class);

        $client->create(new EntityCreatePayload('entity', []));
    }
}
