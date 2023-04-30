<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support;

/**
 * Implements @see \Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenRequiredInterface
 */
trait ContextTokenRequiredTrait
{
    private string $contextToken;

    public function getContextToken(): string
    {
        return $this->contextToken;
    }

    public function withContextToken(string $contextToken): self
    {
        $that = clone $this;
        $that->contextToken = $contextToken;

        return $that;
    }
}
