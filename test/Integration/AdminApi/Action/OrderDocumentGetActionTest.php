<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\DocumentPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCreateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet\OrderDocumentGetActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet\OrderDocumentGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\OrderDocumentCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\OrderDocumentGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidDocumentIdException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\AbstractFieldFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\DocumentPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\DocumentPayloadCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocument
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCreatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCreateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet\OrderDocumentGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet\OrderDocumentGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\OrderDocumentCreateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\OrderDocumentGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidDocumentIdException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\DocumentNumberAlreadyExistsValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentFileGeneratorTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentIdValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\StateMachineInvalidEntityIdValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection\AdminApiFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\CartMissingOrderRelationValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidUuidValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MediaDuplicatedFileNameValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MediaFileTypeNotSupportedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ScopeNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\UnmappedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\WriteUnexpectedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class OrderDocumentGetActionTest extends TestCase
{
    private static OrderDocumentCreateActionInterface $documentCreate;

    private static EntityCreateActionInterface $entityCreate;

    private static OrderDocumentGetActionInterface $action;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $factory = Factory::createAdminApiFactory();
        $actionClientUtils = $factory->getActionClientUtils();
        self::$documentCreate = new OrderDocumentCreateAction($actionClientUtils, $factory->getBaseFactory()->getJsonStreamUtility());
        self::$entityCreate = new EntityCreateAction($actionClientUtils);
        self::$action = new OrderDocumentGetAction($actionClientUtils);
    }

    public function testGetInvoice(): void
    {
        $orderId = self::$entityCreate->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $result = self::$documentCreate->createDocuments(
            (new OrderDocumentCreatePayload('invoice'))
                ->withAddedDocument(new DocumentPayload($orderId))
        );

        static::assertCount(1, $result->getData());

        $first = $result->getData()->first();

        static::assertIsString($first->documentId);
        static::assertIsString($first->documentDeepLink);

        $result = self::$action->getDocument(new OrderDocumentGetCriteria($first->documentId, $first->documentDeepLink));

        static::assertSame('application/pdf', $result->getMimeType());
        static::assertStringEndsWith('.pdf', $result->getFilename());
        static::assertStringStartsWith('%PDF', (string) $result->getDocument());
    }

    public function testFailToGetInvoiceWithWrongDeepLinkCode(): void
    {
        $orderId = self::$entityCreate->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $result = self::$documentCreate->createDocuments(
            (new OrderDocumentCreatePayload('invoice'))
                ->withAddedDocument(new DocumentPayload($orderId))
        );

        static::assertCount(1, $result->getData());

        $first = $result->getData()->first();

        static::assertIsString($first->documentId);
        static::assertIsString($first->documentDeepLink);

        static::expectException(InvalidDocumentIdException::class);
        self::$action->getDocument(new OrderDocumentGetCriteria($first->documentId, 'wrong-code'));
    }

    public function testFailToGetInvoiceWithWrongDocumentId(): void
    {
        static::expectException(InvalidDocumentIdException::class);
        self::$action->getDocument(new OrderDocumentGetCriteria('00000000000000000000000000000000', 'wrong-code'));
    }

    private function getOrderPayload(): array
    {
        $salesChannelTypeStorefront = '8a243080f92e4c719546314b577cf82b';
        $entitySearch = new EntitySearchAction(Factory::createAdminApiFactory()->getActionClientUtils(), new CriteriaFormatter());
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

        return [
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
            'totalRounding' => \array_diff_key(
                $salesChannel->currency->totalRounding->getArrayCopy(),
                ['apiAlias' => true]
            ),
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
    }
}
