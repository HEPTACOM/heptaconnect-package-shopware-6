# Shopware 6 API Package
#### This is part of HEPTACOM solutions for medium and large enterprises.

## Description

This HEPTAconnect package is all about communicating to Shopware 6 APIs.
You can use it in combination with the Shopware 6 Portal.
Read more in the [documentation](https://heptaconnect.io/) and have a look into the [examples section](./docs/examples).

## Usage

### Installation

1. `composer require heptacom/heptaconnect-package-shopware-6`
2. Use guide for specific situation:
   * [Usage in HEPTAconnect](docs/examples/use-package-in-portal.md)
   * [Standalone usage](docs/examples/use-package-without-framework.md)
3. Follow examples below


### AdminAPI - EntityClient

```php
<?php

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\EntityClient;

/** @var $entityClient EntityClient */
$propertyGroupId = $entityClient->create('property_group', [
    'name' => 'Color',
    'sortingType' => 'position',
    'displayType' => 'color',
    'options' => [[
        'position' => 1,
        'name' => 'Red',
        'colorHexCode' => '#aa0000',
    ], [
        'position' => 2,
        'name' => 'Green',
        'colorHexCode' => '#00aa00',
    ], [
        'position' => 3,
        'name' => 'Blue',
        'colorHexCode' => '#0000aa',
    ]],
]);

$colorNamesByName = $entityClient->groupFieldByField(
    'property_group_option',
    'colorHexCode',
    'name',
    new EqualsFilter('group.id', $propertyGroupId)
);
var_export($colorNamesByName);
// array (
//   '#0000aa' => 'Blue',
//   '#00aa00' => 'Green',
//   '#aa0000' => 'Red',
// )

// paginates automatically
foreach ($entityClient->iterate('product') as $product) {
    // …
}

$countryIsos = $entityClient->aggregate('country', new TermsAggregation('countries', 'iso'))->buckets->getKeys();
var_export($countryIsos->asArray());
// array (
//   0 => 'AD',
//   1 => 'AE',
//   2 => 'AF',
//   3 => 'AG',
//   4 => 'AI',
//   …
```


### AdminAPI - ExtensionClient

```php
<?php

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\ExtensionClient;

/** @var $extensionClient ExtensionClient */
// remote updating security plugin
$extensionClient->upload('/path/to/SwagSecurityPlatform.zip');
$extensionClient->refresh();
$extensionClient->update('SwagSecurityPlatform');

if (!$extensionClient->isInstalled('SwagSecurityPlatform')) {
    $extensionClient->install('SwagSecurityPlatform');
}

if (!$extensionClient->isActive('SwagSecurityPlatform')) {
    $extensionClient->activate('SwagSecurityPlatform');
}
```


### AdminAPI - GenericClient

```php
<?php

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\GenericClient;

/** @var $client GenericClient */
// low amount of parameters
var_export($client->get('_info/version'));
// array (
//   'version' => '6.4.20.0',
// )

// query parameters
var_export($client->get('_action/system-config', [
    'domain' => 'core.update',
]));
// array (
//   'core.update.apiUri' => 'https://update-api.shopware.com',
//   'core.update.channel' => 'stable',
//   'core.update.code' => '',
// )

// JSON body
$client->post('_action/system-config', [
    'key' => 'value',
]);

// header support
$client->post('_action/order/00000000000000000000000000000000/state/complete', [], [], [
    // do not run flows to silently update order state
    'sw-skip-trigger-flow' => 1,
]);
```


### StoreAPI - GenericClient

```php
<?php

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\GenericClient;

/** @var $client GenericClient */
// low amount of parameters
var_export($client->get('context')['token']);
// 12c9a85D538b4795877A95aC908987db

// different methods
var_export(\array_column($client->post('country')['data'], 'iso'));
// array (
//   0 => 'AD',
//   1 => 'AE',
//   2 => 'AF',
//   3 => 'AG',
//   4 => 'AI',
//   …
```


## System requirements

* PHP 7.4 or above


## Changelog

See the attached [CHANGELOG.md](./CHANGELOG.md) file for a complete version history and release notes.


## ADR

See the [Architecture Decision Records](./docs/adr/) to understand decisions made, that influence the structure of this project.


## Additional development requirements

* Make
* Any debugging/coverage php extension like xdebug or pcov
* A running Shopware 6 instance


## Contributing

Thank you for considering contributing to this package! Be sure to sign the [CLA](./CLA.md) after creating the pull request. [![CLA assistant](https://cla-assistant.io/readme/badge/HEPTACOM/heptaconnect-repo-base)](https://cla-assistant.io/HEPTACOM/heptaconnect-package-shopware-6)


### Steps to contribute

1. Fork the repository
2. `git clone yourname/heptaconnect-package-shopware-6`
3. Make your changes to master branch
4. Create your Pull-Request


### Check your changes

1. Compare your code against the [project ADRs](#adr)
2. Check and fix code style `make cs-fix && make cs`
3. Setup Shopware 6 instance for testing. Checkout [dockware.io](https://dockware.io) for a Shopware 6 development instance
   * Set `TEST_ADMIN_API_URL`, `TEST_ADMIN_API_USERNAME`, `TEST_ADMIN_API_PASSWORD` to point to your Shopware 6 instance 
   * Optionally set `TEST_STORE_API_URL`, `TEST_STORE_API_ACCESS_KEY` to point to your Shopware 6 instance. If not set the Admin API credentials will be used to create the data 
4. Check tests `make -e test`
5. Check whether test code coverage is same or higher `make -e coverage`
6. Check whether tests can find future obscurities `make -e infection`


## License

Copyright 2020 HEPTACOM GmbH

Dual licensed under the [GNU Affero General Public License v3.0](./LICENSE.md) (the "License") and proprietary license; you may not use this project except in compliance with the License.
You may obtain a copy of the AGPL License at [https://spdx.org/licenses/AGPL-3.0-or-later.html](https://spdx.org/licenses/AGPL-3.0-or-later.html).
Contact us on [our website](https://www.heptacom.de) for further information about proprietary usage.
