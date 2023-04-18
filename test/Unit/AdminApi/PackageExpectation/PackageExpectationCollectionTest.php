<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\AdminApi\PackageExpectation;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationCollection
 */
final class PackageExpectationCollectionTest extends TestCase
{
    public function testExpecationsAreMergedFromAllProviders(): void
    {
        $collection = new PackageExpectationCollection([
            $this->createProvider(['shopware/core: >=6.4.0']),
            $this->createProvider(['shopware/core: >=6.4.5.0']),
            $this->createProvider([
                'shopware/core: >=6.4.15.0',
                'shopware/core: >=6.4.20.0',
            ]),
        ]);
        $merged = $collection->getMergedExpectedPackages()->asArray();

        static::assertEqualsCanonicalizing([
            'shopware/core: >=6.4.0',
            'shopware/core: >=6.4.5.0',
            'shopware/core: >=6.4.15.0',
            'shopware/core: >=6.4.20.0',
        ], $merged);
    }

    public function testDuplicatesAreRemoved(): void
    {
        $collection = new PackageExpectationCollection([
            $this->createProvider(['shopware/core: >=6.4.0']),
            $this->createProvider(['shopware/core: >=6.4.0']),
            $this->createProvider([
                'shopware/core: >=6.4.5.0',
                'shopware/core: >=6.4.5.0',
            ]),
        ]);
        $merged = $collection->getMergedExpectedPackages()->asArray();

        static::assertEqualsCanonicalizing([
            'shopware/core: >=6.4.0',
            'shopware/core: >=6.4.5.0',
        ], $merged);
    }

    private function createProvider(array $constraints): PackageExpectationInterface
    {
        $result = $this->createMock(PackageExpectationInterface::class);

        $result->method('getPackageExpectation')->willReturn($constraints);

        return $result;
    }
}
