# Use package in portal

Use the package within a portal declare it as additional package:

## Portal

###### src/Portal.php

```php
<?php

namespace Portal;

use Heptacom\HeptaConnect\Package\Shopware6\Shopware6Package;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;

class Portal extends PortalContract
{
    public function getAdditionalPackages() : iterable
    {
        return [
            new Shopware6Package(),
        ];
    }
}
```
