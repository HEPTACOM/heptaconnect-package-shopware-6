<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSorting;

final class TermsAggregation extends AbstractFieldAggregation
{
    private ?FieldSorting $sorting;

    private ?AggregationContract $aggregation;

    public function __construct(
        string $name,
        string $field,
        ?FieldSorting $sorting = null,
        ?AggregationContract $aggregation = null
    ) {
        parent::__construct($name, $field);
        $this->sorting = $sorting;
        $this->aggregation = $aggregation;
    }

    public function getSorting(): ?FieldSorting
    {
        return $this->sorting;
    }

    public function getAggregation(): ?AggregationContract
    {
        return $this->aggregation;
    }
}
