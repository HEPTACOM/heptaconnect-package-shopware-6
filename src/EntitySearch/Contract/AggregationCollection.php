<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<AggregationContract>
 */
final class AggregationCollection extends AbstractObjectCollection
{
    /**
     * @var array<string, AggregationContract>
     */
    protected array $items = [];

    public function offsetSet($offset, $value): void
    {
        if (!$this->isValidItem($value)) {
            throw new \InvalidArgumentException();
        }

        $this->items[$value->getName()] = $value;
    }

    public function push(iterable $items): void
    {
        foreach ($items as $item) {
            $this->offsetSet($item->getName(), $item);
        }
    }

    protected function getT(): string
    {
        return AggregationContract::class;
    }
}
