<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationInterface;

final class AuthenticationMemoryCache implements AuthenticationInterface
{
    private AuthenticationInterface $decorated;

    private ?string $header = null;

    public function __construct(AuthenticationInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function refresh(): void
    {
        $this->header = null;

        $this->decorated->refresh();
    }

    public function getAuthorizationHeader(): string
    {
        $result = $this->header;

        $result ??= $this->decorated->getAuthorizationHeader();
        $this->header = $result;

        return $result;
    }
}
