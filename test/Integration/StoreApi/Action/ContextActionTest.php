<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\StoreApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\ContextGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\ContextUpdateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet\ContextGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate\ContextUpdatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet\CountryGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\CountryGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\Exception\CustomerNotLoggedInException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory as AdminFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\StoreApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidUuidValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\UnmappedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet\ContextGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet\ContextGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate\ContextUpdatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate\ContextUpdateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet\CountryGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet\CountryGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ContextTokenRequiredTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\ContextGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\ContextUpdateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\CountryGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\JsonResponseValidator\CustomerNotLoggedInValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class ContextActionTest extends TestCase
{
    public function testGet(): void
    {
        $action = Factory::createActionClass(ContextGetAction::class);
        $context = $action->getContext(new ContextGetCriteria(null))->getContext();

        static::assertNotNull($context->token);
        static::assertNull($context->customer);

        // not exact version check
        if (\version_compare(AdminFactory::getShopwareVersion(), '6.4.10', '>=')) {
            static::assertSame('user', $context->context->scope);
        }
    }

    public function testFailOnAddressChangeWithNoLoggedInCustomer(): void
    {
        $get = Factory::createActionClass(ContextGetAction::class);
        $update = Factory::createActionClass(ContextUpdateAction::class);

        $defaultContext = $get->getContext(new ContextGetCriteria(null))->getContext();

        // store in the context storage
        $contextToken = $update->updateContext(new ContextUpdatePayload($defaultContext->token))->getContextToken();
        $context = $get->getContext(new ContextGetCriteria($contextToken))->getContext();

        static::assertNull($context->customer);
        static::expectException(CustomerNotLoggedInException::class);

        $update->updateContext((new ContextUpdatePayload($context->token))->withBillingAddressId('651dccfa318b4dd3bdca4e18f57eaedd'));
    }

    public function testChangeCountry(): void
    {
        $get = Factory::createActionClass(ContextGetAction::class);
        $update = Factory::createActionClass(ContextUpdateAction::class);
        $country = Factory::createActionClass(CountryGetAction::class, new CriteriaFormatter());
        $countries = $country->getCountries(new CountryGetCriteria());

        static::assertGreaterThan(1, $countries->getElements()->count());

        $defaultContext = $get->getContext(new ContextGetCriteria(null))->getContext();
        $unknownContext = $get->getContext(new ContextGetCriteria($defaultContext->token))->getContext();

        // remove as they are random
        unset($defaultContext->token);
        unset($unknownContext->token);

        // is an empty and unknown context the same?
        static::assertSame($defaultContext->getArrayCopy(), $unknownContext->getArrayCopy());

        $defaultContext = $get->getContext(new ContextGetCriteria(null))->getContext();

        // store in the context storage
        $storedContextToken = $update->updateContext(new ContextUpdatePayload($defaultContext->token))->getContextToken();
        $storedContext = $get->getContext(new ContextGetCriteria($storedContextToken))->getContext();

        static::assertSame($storedContextToken, $storedContext->token);

        $newCountryId = $countries->getElements()->first()->id;

        if ($newCountryId === $storedContext->shippingLocation->country->id) {
            $newCountryId = $countries->getElements()->last()->id;
        }

        static::assertNotSame($storedContext->shippingLocation->country->id, $newCountryId);

        $newContextToken = $update->updateContext((new ContextUpdatePayload($storedContext->token))->withCountryId($newCountryId))->getContextToken();
        $newContext = $get->getContext(new ContextGetCriteria($newContextToken))->getContext();

        static::assertNotSame($storedContext->shippingLocation->country->id, $newContext->shippingLocation->country->id);
        static::assertSame($newCountryId, $newContext->shippingLocation->country->id);
    }
}
