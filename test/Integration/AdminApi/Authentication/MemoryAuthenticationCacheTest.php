<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryAuthenticationCache;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryAuthenticationCache
 */
final class MemoryAuthenticationCacheTest extends TestCase
{
    public function testCachingLayer(): void
    {
        $decorated = $this->createMock(AuthenticationInterface::class);
        $decorated->expects(static::once())->method('refresh');
        $decorated->expects(static::exactly(2))->method('getAuthorizationHeader')->willReturn('value');

        $service = new MemoryAuthenticationCache($decorated);

        static::assertSame('value', $service->getAuthorizationHeader());
        // repeat it and it will still not trigger the decorated
        static::assertSame('value', $service->getAuthorizationHeader());
        static::assertSame('value', $service->getAuthorizationHeader());
        static::assertSame('value', $service->getAuthorizationHeader());

        $service->refresh();

        // repeat it and it will trigger the decorated once more
        static::assertSame('value', $service->getAuthorizationHeader());
        static::assertSame('value', $service->getAuthorizationHeader());
        static::assertSame('value', $service->getAuthorizationHeader());
        static::assertSame('value', $service->getAuthorizationHeader());
    }

    public function testPassThroughExceptionsOnRefresh(): void
    {
        $decorated = $this->createMock(AuthenticationInterface::class);
        $decorated->expects(static::once())->method('refresh')->willThrowException(new AuthenticationFailed(123));

        $service = new MemoryAuthenticationCache($decorated);

        static::expectException(AuthenticationFailed::class);
        static::expectExceptionCode(123);

        $service->refresh();
    }

    public function testPassThroughExceptionsOnGetAuthorizationHeader(): void
    {
        $decorated = $this->createMock(AuthenticationInterface::class);
        $decorated->expects(static::once())->method('getAuthorizationHeader')->willThrowException(new AuthenticationFailed(123));

        $service = new MemoryAuthenticationCache($decorated);

        static::expectException(AuthenticationFailed::class);
        static::expectExceptionCode(123);

        $service->getAuthorizationHeader();
    }
}
