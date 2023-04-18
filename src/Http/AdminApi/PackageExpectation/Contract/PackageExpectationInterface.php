<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract;

interface PackageExpectationInterface
{
    /**
     * Return Shopware package constraints, that are expected to be valid on the API.
     *
     * @return list<string>
     */
    public function getPackageExpectation(): array;
}
