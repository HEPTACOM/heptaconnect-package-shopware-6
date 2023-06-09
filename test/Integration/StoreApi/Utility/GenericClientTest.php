<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\StoreApi\Utility;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\GenericAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\GenericClient;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\StoreApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic\GenericPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic\GenericResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\GenericAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\JsonResponseValidator\CustomerNotLoggedInValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\DependencyInjection\StoreApiFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\GenericClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\ResourceNotFoundException
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class GenericClientTest extends TestCase
{
    public function testSearchCountries(): void
    {
        $client = $this->createGenericClient();
        $response = $client->post('country');

        static::assertNotEmpty($response['elements']);
    }

    public function testGetContext(): void
    {
        $client = $this->createGenericClient();
        $token = $client->get('context')['token'];

        static::assertIsString($token);
        static::assertNotEmpty($token);
    }

    private function createGenericClient(): GenericClient
    {
        return new GenericClient(new GenericAction(Factory::createStoreApiFactory()->getActionClientUtils()));
    }
}
