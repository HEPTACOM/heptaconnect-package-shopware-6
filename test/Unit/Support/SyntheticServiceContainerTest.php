<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\Exception\ServiceNotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\Exception\ServiceNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class SyntheticServiceContainerTest extends TestCase
{
    public function testEmptyContainer(): void
    {
        $container = new SyntheticServiceContainer([]);

        static::assertFalse($container->has('mixed'));

        try {
            $container->get('mixed');
            static::fail('mixed is not a service, that is expected to be priveded');
        } catch (ServiceNotFoundException $notFoundException) {
            static::assertSame('mixed', $notFoundException->getId());
        }
    }

    public function testFilledContainer(): void
    {
        $container = new SyntheticServiceContainer([
            ContainerInterface::class => $this->createMock(ContainerInterface::class),
        ]);

        static::assertTrue($container->has(ContainerInterface::class));
        $service = $container->get(ContainerInterface::class);

        static::assertInstanceOf(ContainerInterface::class, $service);
    }
}
