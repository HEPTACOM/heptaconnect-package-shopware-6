<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<Entity>
 */
final class EntityCollection extends AbstractObjectCollection
{
    public function asArray(): array
    {
        return \array_map(
            static fn (Entity $entity): array => $entity->getArrayCopy(),
            parent::asArray()
        );
    }

    public static function fromList(array $values): EntityCollection
    {
        return new EntityCollection(\array_map([Entity::class, 'fromAssociative'], $values));
    }

    protected function getT(): string
    {
        return Entity::class;
    }
}
