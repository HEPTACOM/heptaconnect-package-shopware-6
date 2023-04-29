<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package;

use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

final class BaseFactory
{
    public static function createHttpClient(): ClientInterface
    {
        return Psr18ClientDiscovery::find();
    }

    public static function createRequestFactory(): RequestFactoryInterface
    {
        return Psr17FactoryDiscovery::findRequestFactory();
    }

    public static function createResponseFactory(): ResponseFactoryInterface
    {
        return Psr17FactoryDiscovery::findResponseFactory();
    }

    public static function createUriFactory(): UriFactoryInterface
    {
        return Psr17FactoryDiscovery::findUriFactory();
    }

    public static function createStreamFactory(): StreamFactoryInterface
    {
        return Psr17FactoryDiscovery::findStreamFactory();
    }

    public static function createJsonStreamUtility(): JsonStreamUtility
    {
        return new JsonStreamUtility(static::createStreamFactory());
    }

    public static function createSimpleCache(): CacheInterface
    {
        return new Psr16Cache(new ArrayAdapter());
    }
}
