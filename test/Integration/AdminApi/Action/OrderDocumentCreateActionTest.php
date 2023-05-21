<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\DocumentPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\OrderDocumentCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\DocumentNumberAlreadyExistsException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidDocumentFileGeneratorTypeException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaDuplicatedFileNameException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaFileTypeNotSupportedException;
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\OrderDocumentCreateAction
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\DocumentNumberAlreadyExistsException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\DocumentNumberAlreadyExistsValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentFileGeneratorTypeValidator
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaDuplicatedFileNameException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaFileTypeNotSupportedException
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
final class OrderDocumentCreateActionTest extends TestCase
{
    public function testCreateInvoice(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new OrderDocumentCreateAction($factory->getActionClientUtils(), $factory->getBaseFactory()->getJsonStreamUtility());
        $create = new EntityCreateAction($factory->getActionClientUtils());
        $search = new EntitySearchAction($factory->getActionClientUtils(), new CriteriaFormatter());
        $orderId = $create->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $result = $action->createDocuments(
            (new OrderDocumentCreatePayload('invoice'))
                ->withAddedDocument(new DocumentPayload($orderId))
        );

        static::assertCount(1, $result->getData());

        $first = $result->getData()->first();

        static::assertIsString($first->documentId);
        static::assertIsString($first->documentDeepLink);

        $document = $search->search(new EntitySearchCriteria(
            'document',
            (new Criteria())->withIds([$first->documentId])
        ))->getData()->first();

        static::assertSame($first->documentDeepLink, $document->deepLinkCode);
    }

    public function testCreateTwoInvoicesForTheSameOrder(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new OrderDocumentCreateAction($factory->getActionClientUtils(), $factory->getBaseFactory()->getJsonStreamUtility());
        $create = new EntityCreateAction($factory->getActionClientUtils());
        $orderId = $create->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $result = $action->createDocuments(
            (new OrderDocumentCreatePayload('invoice'))
                ->withAddedDocument(new DocumentPayload($orderId))
                ->withAddedDocument(new DocumentPayload($orderId))
        );

        static::assertCount(1, $result->getData());
        static::assertIsString($result->getData()->first()->documentId);
        static::assertIsString($result->getData()->first()->documentDeepLink);
    }

    public function testCreateTwoInvoicesForTheDifferentOrder(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new OrderDocumentCreateAction($factory->getActionClientUtils(), $factory->getBaseFactory()->getJsonStreamUtility());
        $create = new EntityCreateAction($factory->getActionClientUtils());
        $orderId1 = $create->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $orderId2 = $create->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $result = $action->createDocuments(
            (new OrderDocumentCreatePayload('invoice'))
                ->withAddedDocument(new DocumentPayload($orderId1))
                ->withAddedDocument(new DocumentPayload($orderId2))
        );

        static::assertCount(2, $result->getData());
        static::assertIsString($result->getData()->first()->documentId);
        static::assertIsString($result->getData()->first()->documentDeepLink);
        static::assertIsString($result->getData()->last()->documentId);
        static::assertIsString($result->getData()->last()->documentDeepLink);
    }

    public function testCreateTwoInvoicesForTheDifferentOrderButWithSameDocumentNumber(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new OrderDocumentCreateAction($factory->getActionClientUtils(), $factory->getBaseFactory()->getJsonStreamUtility());
        $create = new EntityCreateAction($factory->getActionClientUtils());
        $orderId1 = $create->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $orderId2 = $create->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $documentNumber = \bin2hex(\random_bytes(16));

        $result = $action->createDocuments(
            (new OrderDocumentCreatePayload('invoice'))
                ->withAddedDocument((new DocumentPayload($orderId1))->withConfiguration([
                    'documentNumber' => $documentNumber,
                ]))
                ->withAddedDocument((new DocumentPayload($orderId2))->withConfiguration([
                    'documentNumber' => $documentNumber,
                ]))
        );

        static::assertCount(1, $result->getData());
        static::assertNotEmpty($result->getErrors());
        static::assertNotEmpty(\array_filter(
            $result->getErrors(),
            static fn (\Throwable $exception): bool => $exception instanceof MediaDuplicatedFileNameException // new method
            || $exception instanceof DocumentNumberAlreadyExistsException // old method
        ));
    }

    public function testFailCreateTwoInvoicesWithUnsupportedFileTypeButSucceedOne(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new OrderDocumentCreateAction($factory->getActionClientUtils(), $factory->getBaseFactory()->getJsonStreamUtility());
        $create = new EntityCreateAction($factory->getActionClientUtils());
        $orderId1 = $create->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $orderId2 = $create->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $result = $action->createDocuments(
            (new OrderDocumentCreatePayload('invoice'))
                ->withAddedDocument(new DocumentPayload($orderId1))
                ->withAddedDocument((new DocumentPayload($orderId2))->withFileType('exe'))
        );

        static::assertNotEmpty($result->getErrors());
        static::assertNotEmpty(\array_filter(
            $result->getErrors(),
            static fn (\Throwable $exception): bool => $exception instanceof InvalidDocumentFileGeneratorTypeException // new method
                || $exception instanceof MediaFileTypeNotSupportedException // old method
        ));

        static::assertCount(1, $result->getData());
        static::assertIsString($result->getData()->first()->documentId);
        static::assertIsString($result->getData()->first()->documentDeepLink);
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
