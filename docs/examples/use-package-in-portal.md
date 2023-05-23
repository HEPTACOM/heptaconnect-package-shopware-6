# Use package in portal

Use the package within a portal declare it as additional package:

## Portal

###### src/Portal.php

```php
<?php

namespace Portal;

use Heptacom\HeptaConnect\Package\Shopware6\Shopware6Package;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Portal extends PortalContract
{
    public function getAdditionalPackages() : iterable
    {
        yield new Shopware6Package();
    }
    
    public function getConfigurationTemplate(): OptionsResolver
    {
        $resolver = parent::getConfigurationTemplate();

        $this->configureAdminApi($resolver);
        $this->configureStoreApi($resolver);

        return $resolver;
    }

    private function configureAdminApi(OptionsResolver $resolver): void
    {
        $resolver->define('admin_api_grant_type')
            ->allowedTypes('string')
            ->allowedValues('client_credentials', 'password')
            ->default('client_credentials');

        $resolver->define('admin_api_url')
            ->allowedTypes('string')
            ->default('');

        $resolver->define('admin_api_username')
            ->allowedTypes('string')
            ->default('');

        $resolver->define('admin_api_secret')
            ->allowedTypes('string')
            ->default('');

        $resolver->define('admin_api_scopes')
            ->allowedTypes('array')
            ->default(['write']);
    }

    private function configureStoreApi(OptionsResolver $resolver): void
    {
        $resolver->define('store_api_url')
            ->allowedTypes('string')
            ->default('');

        $resolver->define('store_api_access_key')
            ->allowedTypes('string')
            ->default('');
    }
}
```

###### src/Http/AdminApi/Authentication/ApiConfigurationFactory.php

```php
<?php

declare(strict_types=1);

namespace Portal\Http\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;

class ApiConfigurationFactory
{
    public function __construct(
        private string $configAdminApiGrantType,
        private string $configAdminApiUrl,
        private string $configAdminApiUsername,
        private string $configAdminApiSecret,
        private array $configAdminApiScopes,
    ) {
    }

    public function factory(): ApiConfiguration
    {
        return new ApiConfiguration(
            $this->configAdminApiGrantType,
            $this->configAdminApiUrl,
            $this->configAdminApiUsername,
            $this->configAdminApiSecret,
            $this->configAdminApiScopes,
        );
    }
}
```

###### src/Http/StoreApi/Authentication/ApiConfigurationFactory.php

```php
<?php

declare(strict_types=1);

namespace Portal\Http\StoreApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration;

class ApiConfigurationFactory
{
    public function __construct(
        private string $configStoreApiUrl,
        private string $configStoreApiAccessKey,
    ) {
    }

    public function factory(): ApiConfiguration
    {
        return new ApiConfiguration(
            $this->configStoreApiUrl,
            $this->configStoreApiAccessKey,
        );
    }
}
```

###### src/Resources/config/services.xml

```xml
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration">
            <factory service="Portal\Http\AdminApi\Authentication\ApiConfigurationFactory" method="factory"/>
        </service>

        <service id="Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration">
            <factory service="Portal\Http\StoreApi\Authentication\ApiConfigurationFactory" method="factory"/>
        </service>
    </services>
</container>
```
