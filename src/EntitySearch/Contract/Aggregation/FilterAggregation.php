<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection;

final class FilterAggregation extends AggregationContract
{
    private AggregationContract $aggregation;

    private FilterCollection $filters;

    public function __construct(
        string $name,
        AggregationContract $aggregation,
        FilterCollection $filters
    ) {
        parent::__construct($name);
        $this->aggregation = $aggregation;
        $this->filters = $filters;
    }

    public function getAggregation(): AggregationContract
    {
        return $this->aggregation;
    }

    public function getFilters(): FilterCollection
    {
        return $this->filters;
    }
}
