<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Support;

/**
 * Implements @see \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\ExpectedPackagesAwareInterface
 */
trait ExpectedPackagesAwareTrait
{
    /**
     * @var array<string, string[]>
     */
    private array $expectedPackages = [];

    public function getExpectedPackageVersionConstraints(): array
    {
        return $this->expectedPackages;
    }

    public function withExpectedPackage(string $package, string $constraint): self
    {
        $this->validateExpectedPackageName($package);
        $this->validateExpectedPackageConstraint($constraint);

        $this->expectedPackages[$package] = [$constraint];

        return $this;
    }

    public function withAddedExpectedPackage(string $package, string $constraint): self
    {
        $this->validateExpectedPackageName($package);
        $this->validateExpectedPackageConstraint($constraint);

        $this->expectedPackages[$package][] = $constraint;

        return $this;
    }

    public function withoutExpectedPackage(string $package): self
    {
        $this->validateExpectedPackageName($package);

        unset($this->expectedPackages[$package]);

        return $this;
    }

    public function withoutExpectedPackages(): self
    {
        $this->expectedPackages = [];

        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     */
    private function validateExpectedPackageName(string $package): void
    {
        if ($package === '') {
            throw new \UnexpectedValueException('$package needs to be non empty', 1680447700);
        }

        $separatorPos = \mb_strpos($package, '/');

        if (($separatorPos === false) || ($separatorPos === 0) || ($separatorPos === \mb_strlen($package) - 1)) {
            throw new \UnexpectedValueException('$package needs to follow the composer name format <vendor>/<package-name>', 1680447701);
        }
    }

    /**
     * @throws \UnexpectedValueException
     */
    private function validateExpectedPackageConstraint(string $constraint): void
    {
        if ($constraint === '') {
            throw new \UnexpectedValueException('$constraint needs to be non empty', 1680447702);
        }
    }
}
