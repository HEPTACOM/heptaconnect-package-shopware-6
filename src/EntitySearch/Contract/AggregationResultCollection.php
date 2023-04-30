<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<AggregationResult>
 */
final class AggregationResultCollection extends AbstractObjectCollection
{
    /**
     * @var array<string, AggregationResult>
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

    public static function fromList(array $values): AggregationResultCollection
    {
        return new AggregationResultCollection(\array_map(
            static function (string $name, array $data): AggregationResult {
                $entities = $data['entities'] ?? null;

                if ($entities !== null) {
                    $data['entities'] = EntityCollection::fromList($entities);
                }

                $buckets = $data['buckets'] ?? null;

                if ($buckets !== null) {
                    $data['buckets'] = AggregationBucketCollection::fromList($buckets);
                }

                return new AggregationResult($name, $data);
            },
            \array_keys($values),
            $values
        ));
    }

    protected function getT(): string
    {
        return AggregationResult::class;
    }
}
