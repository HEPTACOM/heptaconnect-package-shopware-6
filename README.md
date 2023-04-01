# Shopware 6 API Package
#### This is part of HEPTACOM solutions for medium and large enterprises.

## Description

This HEPTAconnect package is all about communicating to Shopware 6 APIs.
You can use it in combination with the Shopware 6 Portals.
Read more in the [documentation](https://heptaconnect.io/) and have a look into the [examples section](./docs/examples).


## System requirements

* PHP 7.4 or above


## Changelog

See the attached [CHANGELOG.md](./CHANGELOG.md) file for a complete version history and release notes.


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

1. Check and fix code style `make cs-fix && make cs`
2. Setup Shopware 6 instance for testing. Checkout [dockware.io](https://dockware.io) for a Shopware 6 development instance
   * Set `TEST_ADMIN_API_URL`, `TEST_ADMIN_API_USERNAME`, `TEST_ADMIN_API_PASSWORD` to point to your Shopware 6 instance 
3. Check tests `make -e test`
4. Check whether test code coverage is same or higher `make -e coverage`
5. Check whether tests can find future obscurities `make -e infection`


## License

Copyright 2020 HEPTACOM GmbH

Dual licensed under the [GNU Affero General Public License v3.0](./LICENSE.md) (the "License") and proprietary license; you may not use this project except in compliance with the License.
You may obtain a copy of the AGPL License at [https://spdx.org/licenses/AGPL-3.0-or-later.html](https://spdx.org/licenses/AGPL-3.0-or-later.html).
Contact us on [our website](https://www.heptacom.de) for further information about proprietary usage.
