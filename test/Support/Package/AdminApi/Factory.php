<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\TestBootstrapper;

final class Factory
{
    public static function createApiConfigurationStorage(): ApiConfigurationStorageInterface
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
