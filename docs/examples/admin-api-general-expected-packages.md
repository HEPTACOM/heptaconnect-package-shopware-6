# Use AdminAPI general package expectations

Use package expectations with the Admin API, that are applicable for every AdminAPI communication:

## Portal

###### src/Http/ShopwareVersionPackageExpectation.php

Provider for package expectations implementation.

```php
<?php

namespace Portal\Http;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationInterface;

class ShopwareVersionPackageExpectation implements PackageExpectationInterface
{
    public function getPackageExpectation(): array
    {
        return [
            'shopware/core: >=6.4.10',
        ];
    }
}
```


###### src/Portal.php

Tag service so it is picked up as provider for package expectations.

```php
<?php

namespace Portal;

use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\AdminApiPackageExpectationRegistrationCompilerPass;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Portal extends PortalContract
{
    public function buildContainer(ContainerBuilder $containerBuilder) : void
    {
        parent::buildContainer($containerBuilder);

        $containerBuilder->addCompilerPass(
            new AdminApiPackageExpectationRegistrationCompilerPass(),
            AdminApiPackageExpectationRegistrationCompilerPass::PASS_TYPE,
            AdminApiPackageExpectationRegistrationCompilerPass::PASS_PRIORITY,
        );
    }
}
```


###### src/Resources/config/services.xml

Tag service so it is picked up as provider for package expectations. Alternative to `src/Portal.php`.

```xml
<?xml version="1.0" ?>
<container
    xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd"
>
    <services>
        <instanceof id="Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationInterface">
            <tag name="heptaconnect.package.shopware6.admin_api.package_expectation"/>
        </instanceof>
    </services>
</container>
```
