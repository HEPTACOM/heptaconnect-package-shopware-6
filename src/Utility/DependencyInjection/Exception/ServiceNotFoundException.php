<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\Exception;

use Psr\Container\NotFoundExceptionInterface;

final class ServiceNotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{
    private string $id;

    public function __construct(string $id, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Service by id "' . $id . '" not found', $code, $previous);
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
