<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\ApiConfigurationStorageInterface;

final class MemoryApiConfigurationStorage implements ApiConfigurationStorageInterface
{
    private ApiConfiguration $apiConfiguration;

    public function __construct(ApiConfiguration $apiConfiguration)
    {
        $this->apiConfiguration = $apiConfiguration;
    }

    public function getConfiguration(): ApiConfiguration
    {
        return $this->apiConfiguration;
    }
}
