<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;

final class MemoryApiConfigurationStorage implements ApiConfigurationStorageInterface
{
    private ApiConfiguration $configuration;

    public function __construct(ApiConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): ApiConfiguration
    {
        return $this->configuration;
    }
}
