<?php

declare(strict_types=1);

use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory as AdminFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\StoreApi\Factory as StoreFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\TestBootstrapper;

require __DIR__ . '/../vendor/autoload.php';

TestBootstrapper::instance()->bootstrap();
StoreFactory::createApiConfiguration(); // create Store API credentials with Admin API but before any tests are run to skip coverage
AdminFactory::getShopwareVersion(); // Reads Shopware version but before any tests are run to skip coverage
