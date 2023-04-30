<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract;

/**
 * Access a storage, where the currently usable authentication information is stored.
 */
interface AuthenticationInterface
{
    /**
     * Returns an authorization header value.
     */
    public function getAccessKey(): string;
}
