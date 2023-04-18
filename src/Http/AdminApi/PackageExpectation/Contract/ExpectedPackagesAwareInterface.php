<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract;

/**
 * Describes a struct carrying information about the expected packages available in Shopware.
 */
interface ExpectedPackagesAwareInterface
{
    /**
     * Returns the version constraints for each package.
     *
     * @return array<string, string[]>
     */
    public function getExpectedPackageVersionConstraints(): array;

    /**
     * Sets the version constraint for the given package.
     *
     * @throws \UnexpectedValueException when either $package or $constraint are not in a valid format
     *
     * @return static
     */
    public function withExpectedPackage(string $package, string $constraint): self;

    /**
     * Adds the version constraint for the given package.
     *
     * @throws \UnexpectedValueException when either $package or $constraint are not in a valid format
     *
     * @return static
     */
    public function withAddedExpectedPackage(string $package, string $constraint): self;

    /**
     * Removes the version constraint for the given package.
     *
     * @throws \UnexpectedValueException when either $package are not in a valid format
     *
     * @return static
     */
    public function withoutExpectedPackage(string $package): self;

    /**
     * Removes version constraints for all packages.
     *
     * @return static
     */
    public function withoutExpectedPackages(): self;
}
