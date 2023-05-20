<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\AdminApi\Utility\DependencyInjection;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticationMemoryCache;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection\AdminApiFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection\AdminApiFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class AdminApiFactoryTest extends TestCase
{
    public function testEverythingHasFallbackBehaviour(): void
    {
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []));

        foreach (\get_class_methods($adminApiFactory) as $method) {
            if (!\str_starts_with($method, 'get')) {
                continue;
            }

            $result = $adminApiFactory->$method();

            static::assertNotNull($result);
            static::assertIsObject($result);
        }
    }

    public function testReplaceBaseFactory(): void
    {
        $baseFactory = new BaseFactory();
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), $baseFactory);

        static::assertSame($baseFactory, $adminApiFactory->getBaseFactory());
    }

    public function testReplaceConfigurationStorage(): void
    {
        $storage = $this->createMock(ApiConfigurationStorageInterface::class);
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            ApiConfigurationStorageInterface::class => $storage,
        ])));

        static::assertInstanceOf(MockObject::class, $adminApiFactory->getApiConfigurationStorage());
        static::assertSame($storage, $adminApiFactory->getApiConfigurationStorage());

        $storage = new MemoryApiConfigurationStorage(new ApiConfiguration('', '', '', '', []));
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            MemoryApiConfigurationStorage::class => $storage,
        ])));

        static::assertSame($storage, $adminApiFactory->getApiConfigurationStorage());
    }

    public function testReplaceJsonResponseValidatorCollection(): void
    {
        $collection = new JsonResponseValidatorCollection();
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseValidatorCollection::class => $collection,
        ])));

        static::assertSame($collection, $adminApiFactory->getJsonResponseValidatorCollection());

        $collection = new JsonResponseValidatorCollection();
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseValidatorCollection::class . '.admin_api' => $collection,
        ])));

        static::assertSame($collection, $adminApiFactory->getJsonResponseValidatorCollection());

        $collection = new JsonResponseValidatorCollection();
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseValidatorCollection::class . '.store_api' => $collection,
        ])));

        static::assertNotSame($collection, $adminApiFactory->getJsonResponseValidatorCollection());
    }

    public function testReplaceJsonResponseErrorHandler(): void
    {
        $service = $this->createMock(ErrorHandlerInterface::class);
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            ErrorHandlerInterface::class => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $adminApiFactory->getJsonResponseErrorHandler());
        static::assertSame($service, $adminApiFactory->getJsonResponseErrorHandler());

        $service = new JsonResponseErrorHandler($adminApiFactory->getBaseFactory()->getJsonStreamUtility(), new JsonResponseValidatorCollection());
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseErrorHandler::class => $service,
        ])));

        static::assertSame($service, $adminApiFactory->getJsonResponseErrorHandler());

        $service = $this->createMock(ErrorHandlerInterface::class);
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            ErrorHandlerInterface::class . '.admin_api' => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $adminApiFactory->getJsonResponseErrorHandler());
        static::assertSame($service, $adminApiFactory->getJsonResponseErrorHandler());

        $service = new JsonResponseErrorHandler($adminApiFactory->getBaseFactory()->getJsonStreamUtility(), new JsonResponseValidatorCollection());
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            JsonResponseErrorHandler::class . '.admin_api' => $service,
        ])));

        static::assertSame($service, $adminApiFactory->getJsonResponseErrorHandler());
    }

    public function testReplaceAuthenticatedHttpClientInterface(): void
    {
        $service = $this->createMock(AuthenticatedHttpClientInterface::class);
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            AuthenticatedHttpClientInterface::class => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $adminApiFactory->getAuthenticatedClient());
        static::assertSame($service, $adminApiFactory->getAuthenticatedClient());

        $service = new AuthenticatedHttpClient($service, $this->createMock(AuthenticationInterface::class));
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            AuthenticatedHttpClient::class => $service,
        ])));

        static::assertSame($service, $adminApiFactory->getAuthenticatedClient());
    }

    public function testReplaceAuthenticationInterface(): void
    {
        $service = $this->createMock(AuthenticationInterface::class);
        $storeApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            AuthenticationInterface::class => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $storeApiFactory->getAuthentication());
        static::assertSame($service, $storeApiFactory->getAuthentication());

        $service = new Authentication(
            $this->createMock(CacheInterface::class),
            $this->createMock(JsonStreamUtility::class),
            $this->createMock(RequestFactoryInterface::class),
            $this->createMock(ClientInterface::class),
            $this->createMock(ApiConfigurationStorageInterface::class)
        );
        $storeApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            Authentication::class => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getAuthentication());

        $service = new AuthenticationMemoryCache($this->createMock(AuthenticationInterface::class));
        $storeApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            AuthenticationMemoryCache::class => $service,
        ])));

        static::assertSame($service, $storeApiFactory->getAuthentication());
    }

    public function testReplaceApiConfigurationStorageInterface(): void
    {
        $service = $this->createMock(ApiConfigurationStorageInterface::class);
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            ApiConfigurationStorageInterface::class => $service,
        ])));

        static::assertInstanceOf(MockObject::class, $adminApiFactory->getApiConfigurationStorage());
        static::assertSame($service, $adminApiFactory->getApiConfigurationStorage());

        $service = new MemoryApiConfigurationStorage($adminApiFactory->getApiConfiguration());
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            MemoryApiConfigurationStorage::class => $service,
        ])));

        static::assertSame($service, $adminApiFactory->getApiConfigurationStorage());
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
        $adminApiFactory = new AdminApiFactory(new ApiConfiguration('', '', '', '', []), new BaseFactory(new SyntheticServiceContainer([
            ActionClientUtils::class => $service,
        ])));

        static::assertSame($service, $adminApiFactory->getActionClientUtils());
    }
}
