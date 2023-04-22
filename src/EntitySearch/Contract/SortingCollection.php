<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<SortingContract>
 */
final class SortingCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return SortingContract::class;
    }
}
