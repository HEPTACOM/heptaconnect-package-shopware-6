<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\EntityStateTransition\EntityStateTransitionPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\EntityStateTransitionAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\StateMachineInvalidEntityIdException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\CartMissingOrderRelationException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnknownError;
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\EntityStateTransition\EntityStateTransitionPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\EntityStateTransition\EntityStateTransitionResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\EntityStateTransitionAction
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\StateMachineInvalidEntityIdException
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\CartMissingOrderRelationException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnknownError
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
final class EntityStateTransitionActionTest extends TestCase
{
    public function testTransitionOrder(): void
    {
        $actionClientUtils = Factory::createAdminApiFactory()->getActionClientUtils();
        $action = new EntityStateTransitionAction($actionClientUtils);
        $entityCreate = new EntityCreateAction($actionClientUtils);
        $orderId = $entityCreate->create(new EntityCreatePayload('order', $this->getOrderPayload()))->getId();
        $result = $action->transitionState(new EntityStateTransitionPayload('order', $orderId, 'process'));

        static::assertSame('in_progress', $result->getState()->technicalName);
    }

    public function testTransitionOrderFailsOnMissingOrderCustomer(): void
    {
        $actionClientUtils = Factory::createAdminApiFactory()->getActionClientUtils();
        $action = new EntityStateTransitionAction($actionClientUtils);
        $entityCreate = new EntityCreateAction($actionClientUtils);
        $payload = $this->getOrderPayload();
        unset($payload['orderCustomer']);
        $orderId = $entityCreate->create(new EntityCreatePayload('order', $payload))->getId();

        if (\version_compare(Factory::getShopwareVersion(), '6.4.16', '>=')) {
            static::expectException(UnknownError::class);
        } else {
            static::expectException(CartMissingOrderRelationException::class);
        }

        $action->transitionState(new EntityStateTransitionPayload('order', $orderId, 'process'));
    }

    public function testTransitionOrderFailsWithoutValidOrderId(): void
    {
        $action = new EntityStateTransitionAction(Factory::createAdminApiFactory()->getActionClientUtils());
        $orderId = '00000000000000000000000000000000';

        static::expectException(StateMachineInvalidEntityIdException::class);

        $action->transitionState(new EntityStateTransitionPayload('order', $orderId, 'process'));
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

        $customerEmail = \bin2hex(\random_bytes(16)) . '@test.test';
        $customerNumber = \bin2hex(\random_bytes(16));
        $addressId = \bin2hex(\random_bytes(16));

        return [
            'salesChannelId' => $salesChannel->id,
            'orderNumber' => \bin2hex(\random_bytes(16)),
            'orderCustomer' => [
                'customerNumber' => $customerNumber,
                'salesChannelId' => $salesChannel->id,
                'firstName' => 'Firstname',
                'lastName' => 'Lastname',
                'salutationId' => $salutationId,
                'email' => $customerEmail,
                // only needed for newer Shopware (~6.4.20) versions
                'customer' => [
                    'customerNumber' => $customerNumber,
                    'groupId' => $entitySearch->search(new EntitySearchCriteria('customer-group', new Criteria()))->getData()->first()->id,
                    'defaultPaymentMethodId' => $entitySearch->search(new EntitySearchCriteria('payment-method', new Criteria()))->getData()->first()->id,
                    'salesChannelId' => $salesChannel->id,
                    'firstName' => 'Firstname',
                    'lastName' => 'Lastname',
                    'salutationId' => $salutationId,
                    'email' => $customerEmail,
                    'defaultShippingAddressId' => $addressId,
                    'defaultBillingAddressId' => $addressId,
                    'addresses' => [[
                        'id' => $addressId,
                        'firstName' => 'Firstname',
                        'lastName' => 'Lastname',
                        'salutationId' => $salutationId,
                        'countryId' => $salesChannel->countryId,
                        'street' => 'Street',
                        'zipcode' => '12345',
                        'city' => 'City',
                    ]],
                ],
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
