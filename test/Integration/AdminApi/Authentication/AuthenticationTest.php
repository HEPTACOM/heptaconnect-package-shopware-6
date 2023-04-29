<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class AuthenticationTest extends TestCase
{
    public function testPasswordAuthentication(): void
    {
        $service = new Authentication(
            BaseFactory::createSimpleCache(),
            BaseFactory::createJsonStreamUtility(),
            BaseFactory::createRequestFactory(),
            BaseFactory::createHttpClient(),
            Factory::createApiConfigurationStorage(),
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
