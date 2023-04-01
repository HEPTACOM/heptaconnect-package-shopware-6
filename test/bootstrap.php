<?php

declare(strict_types=1);

use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\TestBootstrapper;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Support/TestBootstrapper.php';

TestBootstrapper::instance()->bootstrap();
