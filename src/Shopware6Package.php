<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;

if (!\class_exists(PackageContract::class)) {
    // `composer require heptacom/heptaconnect-portal-base` to add ability to act as HEPTAconnect package for good integration into portals https://heptaconnect.io/
    return;
}

final class Shopware6Package extends PackageContract
{
}
