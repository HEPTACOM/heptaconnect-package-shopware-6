<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

/**
 * @extends \ArrayObject<string, scalar|Entity|EntityCollection|null>
 *
 * @property string $apiAlias
 * @property string $key
 */
final class AggregationBucket extends \ArrayObject
{
    public function __construct(array $data)
    {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }

    public static function fromAssociative(array $data): AggregationBucket
    {
        foreach ($data as $key => &$value) {
            if ($key === 'buckets' && \is_array($value)) {
                $value = AggregationBucketCollection::fromList($value);
            }
        }

        return new AggregationBucket($data);
    }
}
