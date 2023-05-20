# Use package without framework

Use the package without a framework:

## Admin API Project

###### index.php

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\GenericAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection\AdminApiFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\GenericClient;

$factory = new AdminApiFactory(new ApiConfiguration('password', 'https://my-fancy.shop.test/api/', 'admin', 'shopware', ['write']));
$genericClient = new GenericClient(new GenericAction($factory->getActionClientUtils()));
$version = $genericClient->get('_info/version');
```


## Store API Project

###### index.php

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\GenericAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\DependencyInjection\StoreApiFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\GenericClient;

$factory = new StoreApiFactory(new ApiConfiguration('https://my-fancy.shop.test/store-api', 'SWSC0123456789'));
$genericClient = new GenericClient(new GenericAction($factory->getActionClientUtils()));
$context = $genericClient->get('context');
```
