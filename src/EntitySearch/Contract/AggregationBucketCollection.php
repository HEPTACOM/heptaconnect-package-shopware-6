<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<AggregationBucket>
 */
final class AggregationBucketCollection extends AbstractObjectCollection
{
    /**
     * @var array<string, AggregationBucket>
     */
    protected array $items = [];

    public function offsetSet($offset, $value): void
    {
        if (!$this->isValidItem($value)) {
            throw new \InvalidArgumentException();
        }

        $this->items[$value->key] = $value;
    }

    public function push(iterable $items): void
    {
        foreach ($items as $item) {
            $this->offsetSet($item->key, $item);
        }
    }

    public function getKeys(): StringCollection
    {
        return new StringCollection(\array_map('strval', \array_keys($this->items)));
    }

    public static function fromList(array $values): AggregationBucketCollection
    {
        return new AggregationBucketCollection(\array_map([AggregationBucket::class, 'fromAssociative'], $values));
    }

    protected function getT(): string
    {
        return AggregationBucket::class;
    }
}
