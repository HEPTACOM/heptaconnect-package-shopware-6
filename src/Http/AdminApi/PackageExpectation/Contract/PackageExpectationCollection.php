<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<PackageExpectationInterface>
 */
final class PackageExpectationCollection extends AbstractObjectCollection
{
    public function getMergedExpectedPackages(): StringCollection
    {
        $result = new StringCollection();

        /** @var PackageExpectationInterface $packageExpectation */
        foreach ($this as $packageExpectation) {
            $result->push($packageExpectation->getPackageExpectation());
        }

        return new StringCollection(\array_unique($result->asArray(), \SORT_STRING));
    }

    protected function getT(): string
    {
        return PackageExpectationInterface::class;
    }
}
