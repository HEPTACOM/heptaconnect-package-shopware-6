<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed;

/**
 * Calculates and provide the usable authentication information.
 */
interface AuthenticationInterface
{
    /**
     * Performs an authorization request with the configured credentials.
     *
     * @throws AuthenticationFailed
     */
    public function refresh(): void;

    /**
     * Returns an authorization header value.
     *
     * @throws AuthenticationFailed
     */
    public function getAuthorizationHeader(): string;
}
