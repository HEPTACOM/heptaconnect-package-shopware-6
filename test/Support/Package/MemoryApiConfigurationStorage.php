<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\TestBootstrapper;

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

    public static function createBootstrapped(): ApiConfigurationStorageInterface
    {
        return new MemoryApiConfigurationStorage(new ApiConfiguration(
            'password',
            TestBootstrapper::instance()->getAdminApiUrl(),
            TestBootstrapper::instance()->getAdminApiUsername(),
            TestBootstrapper::instance()->getAdminApiPassword(),
            ['write'],
        ));
    }
}
