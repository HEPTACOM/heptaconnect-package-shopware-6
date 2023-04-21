<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<FieldSorting>
 */
final class FieldSortingCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return FieldSorting::class;
    }
}
