<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Portal\MemoryPortalStorage;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;

abstract class AbstractActionTestCase extends TestCase
{
    protected function createApiConfigurationStorage(): ApiConfigurationStorageInterface
    {
        return MemoryApiConfigurationStorage::createBootstrapped();
    }

    protected function createClient(): AuthenticatedHttpClientInterface
    {
        $client = Psr18ClientDiscovery::find();

        return new AuthenticatedHttpClient(
            $client,
            new PortalNodeStorageAuthenticationStorage(
                new MemoryPortalStorage(),
                $this->createJsonStreamUtility(),
                $this->createRequestFactory(),
                $client,
                $this->createApiConfigurationStorage()
            )
        );
    }

    /**
     * @template TActionClass of AbstractActionClient
     *
     * @param class-string<TActionClass> $actionClass
     * @return AbstractActionClient&TActionClass
     */
    protected function createAction(string $actionClass): AbstractActionClient
    {
        $jsonStreamUtility = $this->createJsonStreamUtility();

        return new $actionClass(
            $this->createClient(),
            $this->createRequestFactory(),
            $this->createApiConfigurationStorage(),
            $jsonStreamUtility,
            new JsonResponseErrorHandler($jsonStreamUtility, [
            ]),
        );
    }

    protected function createRequestFactory(): RequestFactoryInterface
    {
        return Psr17FactoryDiscovery::findRequestFactory();
    }

    protected function createJsonStreamUtility(): JsonStreamUtility
    {
        return new JsonStreamUtility(Psr17FactoryDiscovery::findStreamFactory());
    }
}
