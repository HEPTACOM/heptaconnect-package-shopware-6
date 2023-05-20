<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\StoreApi\Utility\DependencyInjection;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Authentication;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticationMemoryCache;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\DependencyInjection\StoreApiFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\DependencyInjection\StoreApiFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class StoreApiFactoryTest extends TestCase
{
    public function testEverythingHasFallbackBehaviour(): void
    {
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''));

        foreach (\get_class_methods($storeApiFactory) as $method) {
            if (!\str_starts_with($method, 'get')) {
                continue;
            }

            $result = $storeApiFactory->$method();

            static::assertNotNull($result);
            static::assertIsObject($result);
        }
    }

    public function testReplaceBaseFactory(): void
    {
        $baseFactory = new BaseFactory();
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), $baseFactory);

        static::assertSame($baseFactory, $storeApiFactory->getBaseFactory());
    }

    public function testReplaceConfigurationStorage(): void
    {
        $storage = $this->createMock(ApiConfigurationStorageInterface::class);
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            ApiConfigurationStorageInterface::class => $storage,
        ])));

        static::assertInstanceOf(MockObject::class, $storeApiFactory->getApiConfigurationStorage());
        static::assertSame($storage, $storeApiFactory->getApiConfigurationStorage());

        $storage = new MemoryApiConfigurationStorage(new ApiConfiguration('', ''));
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            MemoryApiConfigurationStorage::class => $storage,
        ])));

        static::assertSame($storage, $storeApiFactory->getApiConfigurationStorage());
    }

    public function testReplaceJsonResponseValidatorCollection(): void
    {
        $collection = new JsonResponseValidatorCollection();
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseValidatorCollection::class => $collection,
        ])));

        static::assertSame($collection, $storeApiFactory->getJsonResponseValidatorCollection());

        $collection = new JsonResponseValidatorCollection();
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseValidatorCollection::class . '.store_api' => $collection,
        ])));

        static::assertSame($collection, $storeApiFactory->getJsonResponseValidatorCollection());

        $collection = new JsonResponseValidatorCollection();
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseValidatorCollection::class . '.admin_api' => $collection,
        ])));

        static::assertNotSame($collection, $storeApiFactory->getJsonResponseValidatorCollection());
    }

    public function testReplaceJsonResponseErrorHandler(): void
    {
        $service = $this->createMock(ErrorHandlerInterface::class);
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            ErrorHandlerInterface::class => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $storeApiFactory->getJsonResponseErrorHandler());
        static::assertSame($service, $storeApiFactory->getJsonResponseErrorHandler());

        $service = new JsonResponseErrorHandler($storeApiFactory->getBaseFactory()->getJsonStreamUtility(), new JsonResponseValidatorCollection());
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseErrorHandler::class => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getJsonResponseErrorHandler());

        $service = $this->createMock(ErrorHandlerInterface::class);
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            ErrorHandlerInterface::class . '.store_api' => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $storeApiFactory->getJsonResponseErrorHandler());
        static::assertSame($service, $storeApiFactory->getJsonResponseErrorHandler());

        $service = new JsonResponseErrorHandler($storeApiFactory->getBaseFactory()->getJsonStreamUtility(), new JsonResponseValidatorCollection());
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseErrorHandler::class . '.store_api' => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getJsonResponseErrorHandler());
    }

    public function testReplaceAuthenticatedHttpClientInterface(): void
    {
        $service = $this->createMock(AuthenticatedHttpClientInterface::class);
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            AuthenticatedHttpClientInterface::class => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $storeApiFactory->getAuthenticatedClient());
        static::assertSame($service, $storeApiFactory->getAuthenticatedClient());

        $service = new AuthenticatedHttpClient($service, $this->createMock(AuthenticationInterface::class));
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            AuthenticatedHttpClient::class => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getAuthenticatedClient());
    }

    public function testReplaceAuthenticationInterface(): void
    {
        $service = $this->createMock(AuthenticationInterface::class);
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            AuthenticationInterface::class => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $storeApiFactory->getAuthentication());
        static::assertSame($service, $storeApiFactory->getAuthentication());

        $service = new Authentication(new ApiConfiguration('', ''));
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            Authentication::class => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getAuthentication());

        $service = new AuthenticationMemoryCache($this->createMock(AuthenticationInterface::class));
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            AuthenticationMemoryCache::class => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getAuthentication());
    }

    public function testReplaceApiConfigurationStorageInterface(): void
    {
        $service = $this->createMock(ApiConfigurationStorageInterface::class);
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            ApiConfigurationStorageInterface::class => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $storeApiFactory->getApiConfigurationStorage());
        static::assertSame($service, $storeApiFactory->getApiConfigurationStorage());

        $service = new MemoryApiConfigurationStorage($storeApiFactory->getApiConfiguration());
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            MemoryApiConfigurationStorage::class => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getApiConfigurationStorage());
    }

    public function testReplaceActionClientUtils(): void
    {
        $service = new ActionClientUtils(
            $this->createMock(AuthenticatedHttpClientInterface::class),
            $this->createMock(RequestFactoryInterface::class),
            $this->createMock(ApiConfigurationStorageInterface::class),
            (new BaseFactory())->getJsonStreamUtility(),
            $this->createMock(ErrorHandlerInterface::class)
        );
        $storeApiFactory = new StoreApiFactory(new ApiConfiguration('', ''), new BaseFactory(new SyntheticServiceContainer([
            ActionClientUtils::class => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getActionClientUtils());
    }
}
