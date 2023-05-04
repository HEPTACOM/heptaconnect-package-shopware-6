<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Generic;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericPayload;

final class GenericPayload extends AbstractGenericPayload implements ExpectedPackagesAwareInterface
{
    use ExpectedPackagesAwareTrait;
}
