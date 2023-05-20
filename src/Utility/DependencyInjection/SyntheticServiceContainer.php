<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection;

use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

final class SyntheticServiceContainer implements ContainerInterface
{
    private array $services;

    public function __construct(array $services)
    {
        $this->services = $services;
    }

    public function get(string $id)
    {
        if (!\array_key_exists($id, $this->services)) {
            throw new ServiceNotFoundException($id);
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->services);
    }
}
