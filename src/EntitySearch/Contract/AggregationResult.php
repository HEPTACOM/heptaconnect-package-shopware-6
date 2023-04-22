<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

/**
 * @extends \ArrayObject<string, scalar|Entity|EntityCollection|null>
 */
final class AggregationResult extends \ArrayObject
{
    private string $name;

    public function __construct(string $name, array $data)
    {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
