<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

/**
 * @extends \ArrayObject<string, scalar|Entity|EntityCollection|null>
 */
final class Entity extends \ArrayObject
{
    public function __construct(array $data)
    {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }

    public static function fromAssociative(array $data): Entity
    {
        foreach ($data as &$value) {
            if (!\is_array($value)) {
                continue;
            }

            if (static::isArrayList($value)) {
                if ($value === []) {
                    $value = new EntityCollection();
                } elseif (\is_array($value[0]) && !static::isArrayList($value[0])) {
                    $value = EntityCollection::fromList($value);
                }
            } else {
                $value = static::fromAssociative($value);
            }
        }

        return new Entity($data);
    }

    public function getArrayCopy(): array
    {
        $data = parent::getArrayCopy();

        foreach ($data as &$value) {
            if ($value instanceof Entity) {
                $value = $value->getArrayCopy();
            }

            if ($value instanceof EntityCollection) {
                $value = $value->asArray();
            }
        }

        return $data;
    }

    /**
     * @see https://www.php.net/manual/en/function.array-is-list.php#127044
     * @deprecated with php 8.1
     */
    private static function isArrayList(array $array): bool
    {
        $i = -1;

        foreach ($array as $k => $v) {
            ++$i;

            if ($k !== $i) {
                return false;
            }
        }

        return true;
    }
}
