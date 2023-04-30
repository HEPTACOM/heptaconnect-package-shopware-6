<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticationInterface;

final class AuthenticationMemoryCache implements AuthenticationInterface
{
    private ?string $accessKey = null;

    private AuthenticationInterface $decorated;

    public function __construct(AuthenticationInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function getAccessKey(): string
    {
        $result = $this->accessKey;

        if ($result === null) {
            $result = $this->decorated->getAccessKey();
        }

        return $result;
    }
}
