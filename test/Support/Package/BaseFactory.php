<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package;

use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\StreamFactoryInterface;

final class BaseFactory
{
    public static function createStreamFactory(): StreamFactoryInterface
    {
        return Psr17FactoryDiscovery::findStreamFactory();
    }

    public static function createJsonStreamUtility(): JsonStreamUtility
    {
        return new JsonStreamUtility(static::createStreamFactory());
    }
}
