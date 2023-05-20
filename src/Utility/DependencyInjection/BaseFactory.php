<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection;

use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\Exception\ServiceNotFoundException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use voku\cache\AdapterArray;
use voku\cache\CachePsr16;

/**
 * Use this class to provide shared services, when no dependency injection component is used.
 * When a service needs to be replaced, use the container to provide services by class name or interface name.
 */
final class BaseFactory
{
    private ContainerInterface $container;

    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container ?? new SyntheticServiceContainer([]);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getHttpClient(): ClientInterface
    {
        if ($this->container->has(ClientInterface::class)) {
            return $this->container->get(ClientInterface::class);
        }

        return Psr18ClientDiscovery::find();
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        if ($this->container->has(RequestFactoryInterface::class)) {
            return $this->container->get(RequestFactoryInterface::class);
        }

        return Psr17FactoryDiscovery::findRequestFactory();
    }

    public function getResponseFactory(): ResponseFactoryInterface
    {
        if ($this->container->has(ResponseFactoryInterface::class)) {
            return $this->container->get(ResponseFactoryInterface::class);
        }

        return Psr17FactoryDiscovery::findResponseFactory();
    }

    public function getUriFactory(): UriFactoryInterface
    {
        if ($this->container->has(UriFactoryInterface::class)) {
            return $this->container->get(UriFactoryInterface::class);
        }

        return Psr17FactoryDiscovery::findUriFactory();
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        if ($this->container->has(StreamFactoryInterface::class)) {
            return $this->container->get(StreamFactoryInterface::class);
        }

        return Psr17FactoryDiscovery::findStreamFactory();
    }

    public function getJsonStreamUtility(): JsonStreamUtility
    {
        if ($this->container->has(JsonStreamUtility::class)) {
            return $this->container->get(JsonStreamUtility::class);
        }

        return new JsonStreamUtility($this->getStreamFactory());
    }

    public function getSimpleCache(): CacheInterface
    {
        if ($this->container->has(CacheInterface::class)) {
            return $this->container->get(CacheInterface::class);
        }

        if (\class_exists(ArrayAdapter::class)) {
            if ($this->container->has(ArrayAdapter::class)) {
                return $this->container->get(ArrayAdapter::class);
            }

            return new Psr16Cache(new ArrayAdapter());
        }

        if (\class_exists(CachePsr16::class)) {
            if ($this->container->has(CachePsr16::class)) {
                return $this->container->get(CachePsr16::class);
            }

            // instance without dynamic checks
            return new CachePsr16(new AdapterArray(), null, false, true, false, false);
        }

        throw new ServiceNotFoundException(CacheInterface::class);
    }
}
