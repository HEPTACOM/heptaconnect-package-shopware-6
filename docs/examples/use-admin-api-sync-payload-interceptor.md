# Use AdminAPI sync payload interceptor

Apply interceptors on sync payload in the Admin API:

## Portal

###### src/Http/ForbidProductWritesSyncPayloadInterceptor.php

Interceptor implementation.

```php
<?php

namespace Portal\Http;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperation;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction\SyncPayloadInterceptorInterface;
use Heptacom\HeptaConnect\Portal\Shopware6\Http\ShopwareApiClient\SyncOperation\SyncOperationCollection;

class ForbidProductWritesSyncPayloadInterceptor implements SyncPayloadInterceptorInterface
{
    public function intercept(SyncPayload $payload): SyncPayload
    {
        return $payload->withSyncOperations(new SyncOperationCollection($payload->getSyncOperations()->filter(
            static fn (SyncOperation $operation): bool => $operation->getEntity() !== 'product'
        )));
    }
}
```


###### src/Portal.php

Tag service so it is picked up as interceptor.

```php
<?php

namespace Portal;

use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\AdminApiSyncPayloadInterceptorRegistrationCompilerPass;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Portal extends PortalContract
{
    public function buildContainer(ContainerBuilder $containerBuilder) : void
    {
        parent::buildContainer($containerBuilder);

        $containerBuilder->addCompilerPass(
            new AdminApiSyncPayloadInterceptorRegistrationCompilerPass(),
            AdminApiSyncPayloadInterceptorRegistrationCompilerPass::PASS_TYPE,
            AdminApiSyncPayloadInterceptorRegistrationCompilerPass::PASS_PRIORITY,
        );
    }
}
```


###### src/Resources/config/services.xml

Tag service so it is picked up as interceptor. Alternative to `src/Portal.php`.

```xml
<?xml version="1.0" ?>
<container
    xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd"
>
    <services>
        <instanceof id="Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction\SyncPayloadInterceptorInterface">
            <tag name="heptaconnect.package.shopware6.admin_api.sync_payload_interceptor"/>
        </instanceof>
    </services>
</container>
```
