<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityUpdateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\NotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action\AbstractActionTestCase;
use Http\Discovery\Psr17FactoryDiscovery;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityUpdateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\NotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ResourceNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class EntityUpdateTest extends AbstractActionTestCase
{
    public function testUpdateTag(): void
    {
        $create = $this->createAction(EntityCreateAction::class);
        $name = \bin2hex(\random_bytes(24));
        $result = $create->create(new EntityCreatePayload('tag', [
            'name' => $name,
        ]));

        $newName = \bin2hex(\random_bytes(24));
        $client = $this->createAction(EntityUpdateAction::class);
        $client->update(new EntityUpdatePayload($result->getEntityName(), $result->getId(), [
            'name' => $newName,
        ]));

        $get = $this->createAction(EntityGetAction::class);
        static::assertSame(
            $newName,
            $get->get(
                new EntityGetCriteria($result->getEntityName(), $result->getId(), new Criteria())
            )->getEntity()->name
        );
    }

    public function testEntityFormatWithEntityThatContainsSeparator(): void
    {
        $create = $this->createAction(EntityCreateAction::class);
        $result = $create->create(new EntityCreatePayload('log-entry', [
            'message' => 'An test log message',
            'level' => 5,
            'channel' => 'phpunit',
            'context' => [],
            'extra' => [],
        ]));

        $client = $this->createAction(EntityUpdateAction::class);
        $updateResult = $client->update(new EntityUpdatePayload($result->getEntityName(), $result->getId(), [
            'message' => 'A longer test log message',
        ]));

        static::assertSame('log-entry', $result->getEntityName());
        static::assertSame('log-entry', $updateResult->getEntityName());
        static::assertSame($result->getId(), $updateResult->getId());
    }

    public function testEntityFormatWithWrongEntityNameSeparatorFails(): void
    {
        $client = $this->createAction(EntityUpdateAction::class);

        static::expectException(NotFoundException::class);

        $client->update(new EntityUpdatePayload('log_entry', '00000000000000000000000000000000', []));
    }

    public function testUpdatingAnEntityThatDoesNotExists(): void
    {
        $client = $this->createAction(EntityUpdateAction::class);

        static::expectException(WriteTypeIntendException::class);

        $client->update(new EntityUpdatePayload('country', '00000000000000000000000000000000', [
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
        $client = new EntityUpdateAction(
            $httpClient,
            $this->createRequestFactory(),
            $this->createApiConfigurationStorage(),
            $jsonStreamUtility,
            $this->createJsonResponseErrorHandler($jsonStreamUtility),
        );

        static::expectException(EntityReferenceLocationFormatInvalidException::class);

        $client->update(new EntityUpdatePayload('entity', '00000000000000000000000000000000', []));
    }
}