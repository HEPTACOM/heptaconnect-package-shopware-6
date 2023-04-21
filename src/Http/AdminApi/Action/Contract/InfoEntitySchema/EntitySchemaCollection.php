<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<EntitySchema>
 */
final class EntitySchemaCollection extends AbstractObjectCollection
{
    public function getEntity(string $entity): ?EntitySchema
    {
        foreach ($this as $schema) {
            if ($schema->entity === $entity) {
                return $schema;
            }
        }

        return null;
    }

    public function hasEntity(string $entity): bool
    {
        foreach ($this as $schema) {
            if ($schema->entity === $entity) {
                return true;
            }
        }

        return false;
    }

    protected function getT(): string
    {
        return EntitySchema::class;
    }
}
