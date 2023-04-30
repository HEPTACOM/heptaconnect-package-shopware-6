<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\StoreApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\ContextGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\ContextUpdateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet\ContextGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate\ContextUpdatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\Exception\CustomerNotLoggedInException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\StoreApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ContextTokenRequiredTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\ContextGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\ContextUpdateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\JsonResponseValidator\CustomerNotLoggedInValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\MemoryApiConfigurationStorage
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
        static::assertSame('user', $context->context->scope);
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
}
