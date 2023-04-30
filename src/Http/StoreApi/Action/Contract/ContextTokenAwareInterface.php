<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract;

/**
 * Describes a struct carrying an optional context token.
 */
interface ContextTokenAwareInterface
{
    /**
     * Gets the context token.
     */
    public function getContextToken(): ?string;

    /**
     * Sets the context token.
     *
     * @return static
     */
    public function withContextToken(?string $contextToken): self;
}
