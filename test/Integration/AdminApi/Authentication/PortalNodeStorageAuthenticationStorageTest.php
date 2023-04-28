<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\MemoryApiConfigurationStorage;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class PortalNodeStorageAuthenticationStorageTest extends TestCase
{
    public function testPasswordAuthentication(): void
    {
        $service = new PortalNodeStorageAuthenticationStorage(
            new Psr16Cache(new ArrayAdapter()),
            new JsonStreamUtility(Psr17FactoryDiscovery::findStreamFactory()),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr18ClientDiscovery::find(),
            MemoryApiConfigurationStorage::createBootstrapped(),
        );

        try {
            $service->getAuthorizationHeader();
        } catch (AuthenticationFailed $authenticationFailed) {
            static::assertSame(1680350601, $authenticationFailed->getCode());
        }

        $service->refresh();
        static::assertStringStartsWith('Bearer ey', $service->getAuthorizationHeader());
    }
}
