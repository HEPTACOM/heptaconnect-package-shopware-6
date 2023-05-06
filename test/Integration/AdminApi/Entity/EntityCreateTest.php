<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\NotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\WriteUnexpectedFieldException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\AbstractFieldFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\NotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\ResourceNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\WriteUnexpectedFieldException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\CartMissingOrderRelationValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidUuidValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\UnmappedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\WriteUnexpectedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class EntityCreateTest extends TestCase
{
    public function testCreateTag(): void
    {
        $client = Factory::createActionClass(EntityCreateAction::class);
        $name = \bin2hex(\random_bytes(24));
        $result = $client->create(new EntityCreatePayload('tag', [
            'name' => $name,
        ]));

        $get = Factory::createActionClass(EntityGetAction::class);
        static::assertSame(
            $name,
            $get->get(
                new EntityGetCriteria($result->getEntityName(), $result->getId(), new Criteria())
            )->getEntity()->name
        );
    }

    public function testCreateTagWithPredefinedId(): void
    {
        $client = Factory::createActionClass(EntityCreateAction::class);
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
        $client = Factory::createActionClass(EntityCreateAction::class);
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
        $client = Factory::createActionClass(EntityCreateAction::class);

        static::expectException(NotFoundException::class);

        $client->create(new EntityCreatePayload('log_entry', []));
    }

    public function testCreatingAnEntityThatAlreadyExists(): void
    {
        $client = Factory::createActionClass(EntityCreateAction::class);
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
            BaseFactory::createRequestFactory()
                ->createResponse(204)
                ->withAddedHeader('location', 'http://127.0.0.1/')
        );
        $client = new EntityCreateAction(Factory::createActionClientUtils($httpClient));

        static::expectException(EntityReferenceLocationFormatInvalidException::class);

        $client->create(new EntityCreatePayload('entity', []));
    }

    public function testFailWritingOrderWhenApiAliasKeyIsGivenOnJsonField(): void
    {
        $entitySearch = Factory::createActionClass(EntitySearchAction::class, new CriteriaFormatter());
        $action = Factory::createActionClass(EntityCreateAction::class);
        $salesChannelTypeStorefront = '8a243080f92e4c719546314b577cf82b';
        $salesChannelCriteria = (new Criteria())
            ->withLimit(1)
            ->withAddedAssociation('currency')
            ->withAndFilter(new EqualsFilter('type.id', $salesChannelTypeStorefront));
        $salesChannel = $entitySearch->search(new EntitySearchCriteria('sales-channel', $salesChannelCriteria))->getData()->first();

        $salutationId = $entitySearch->search(new EntitySearchCriteria(
            'salutation',
            (new Criteria())
                ->withAndFilter(new EqualsFilter('salutationKey', 'mr'))
        ))->getData()->first()->id;

        $order = [
            'salesChannelId' => $salesChannel->id,
            'orderNumber' => \bin2hex(\random_bytes(16)),
            'orderCustomer' => [
                'customerNumber' => \bin2hex(\random_bytes(16)),
                'salesChannelId' => $salesChannel->id,
                'firstName' => 'Firstname',
                'lastName' => 'Lastname',
                'salutationId' => $salutationId,
                'email' => \bin2hex(\random_bytes(16)) . '@test.test',
            ],
            'lineItems' => [],
            'orderDateTime' => (new \DateTimeImmutable())->format('c'),
            'currencyId' => $salesChannel->currencyId,
            'billingAddress' => [
                'countryId' => $salesChannel->countryId,
                'salutationId' => $salutationId,
                'firstName' => 'Firstname',
                'lastName' => 'Lastname',
                'street' => 'Street',
                'zipcode' => '12345',
                'city' => 'City',
            ],
            'currencyFactor' => 1.0,
            'stateId' => $entitySearch->search(new EntitySearchCriteria(
                'state-machine-state',
                (new Criteria())
                    ->withAndFilter(new EqualsFilter('stateMachine.technicalName', 'order.state'))
                    ->withAndFilter(new EqualsFilter('technicalName', 'open'))
            ))->getData()->first()->id,
            'shippingCosts' => [
                'unitPrice' => 0.0,
                'totalPrice' => 0.0,
                'quantity' => 0,
                'calculatedTaxes' => [],
                'taxRules' => [],
            ],
            'deliveries' => [],
            'transactions' => [],
            // this will fail due to apiAlias key
            'totalRounding' => $salesChannel->currency->totalRounding->getArrayCopy(),
            'itemRounding' => \array_diff_key(
                $salesChannel->currency->itemRounding->getArrayCopy(),
                ['apiAlias' => true]
            ),
            'price' => [
                'netPrice' => 0,
                'totalPrice' => 0,
                'rawTotal' => 0,
                'calculatedTaxes' => [],
                'taxRules' => [],
                'positionPrice' => 0,
                'taxStatus' => 'gross',
            ],
        ];

        static::expectException(WriteUnexpectedFieldException::class);

        $action->create(new EntityCreatePayload('order', $order));
    }
}
