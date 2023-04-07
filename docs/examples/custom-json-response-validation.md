# Custom JSON response validation

Add custom JSON response validation to support errors, that are not known in stock Shopware

## Portal

###### src/Resources/services.xml

Tag service so it is picked up as validator

```xml
<?xml version="1.0" ?>
<container
    xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd"
>
    <services>
        <instanceof id="Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface">
            <tag name="heptaconnect.package.shopware6.json_response_validator"/>
        </instanceof>
    </services>
</container>
```


###### src/Http/CustomErrorValidator.php

Map the portal node configuration to the Admin API services

```php
<?php

namespace Portal\Http;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CustomErrorValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        if ($error === null) {
            return;
        }

        if ($error['code'] === 'SHOPWARE_EXTENSION__CUSTOM_ERROR') {
            throw new \RuntimeException('My custom error was found');
        }
    }
}
```
