<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract;

abstract class AbstractFieldAggregation extends AggregationContract
{
    private string $field;

    public function __construct(string $name, string $field)
    {
        parent::__construct($name);
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
