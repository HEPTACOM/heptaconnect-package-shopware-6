<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema;

/**
 * @extends \ArrayObject<string, scalar|array|null>
 *
 * @property string                                           $entity
 * @property array<string, array{type: string, flags: array}> $properties
 * @property bool                                             $readProtected
 * @property bool                                             $writeProtected
 */
final class EntitySchema extends \ArrayObject
{
    public function __construct(array $data)
    {
        $data['readProtected'] = $data['read-protected'] ?? null;
        $data['writeProtected'] = $data['write-protected'] ?? null;

        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }
}
