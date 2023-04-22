<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation;

final class EntityAggregation extends AbstractFieldAggregation
{
    private string $entityName;

    public function __construct(string $name, string $field, string $entityName)
    {
        parent::__construct($name, $field);
        $this->entityName = $entityName;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }
}
