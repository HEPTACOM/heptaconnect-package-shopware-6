# Admin API configuration based upon PortalNode configuration

Use portal node configuration as central configuration for the Admin API service:

## Portal

###### src/Portal.php

Add portal node configuration

```php
<?php

namespace Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Portal extends PortalContract
{
    public function getConfigurationTemplate(): OptionsResolver
    {
        return parent::getConfigurationTemplate()
            ->setDefaults([
                'url' => null,
                'username' => null,
                'password' => null,
            ])
            ->setAllowedTypes('url', ['string', null])
            ->setAllowedTypes('username', ['string', null])
            ->setAllowedTypes('password', ['string', null])
        ;
    }
}
```


###### src/Http/AdminApiConfigurationStorage.php

Map the portal node configuration to the Admin API services

```php
<?php

namespace Portal\Http;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;

class AdminApiConfigurationStorage implements ApiConfigurationStorageInterface
{
    private ?string $url;
    private ?string $username;
    private ?string $password;

    public function __construct(
        ?string $configUrl,
        ?string $configUsername,
        ?string $configPassword
    ) {
        $this->url = $configUrl;
        $this->username = $configUsername;
        $this->password = $configPassword;
    }

    public function getConfiguration(): ApiConfiguration
    {
        return new ApiConfiguration('password', $this->url, $this->username, $this->password, ['write']);
    }
}
```
