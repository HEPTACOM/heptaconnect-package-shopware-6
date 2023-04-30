<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticationInterface;

final class Authentication implements AuthenticationInterface
{
    private ApiConfiguration $apiConfiguration;

    public function __construct(ApiConfiguration $apiConfiguration)
    {
        $this->apiConfiguration = $apiConfiguration;
    }

    public function getAccessKey(): string
    {
        return $this->apiConfiguration->getAccessKey();
    }
}
