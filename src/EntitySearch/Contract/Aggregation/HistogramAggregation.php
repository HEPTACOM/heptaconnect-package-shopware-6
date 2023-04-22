<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\SortingContract;

final class HistogramAggregation extends AbstractFieldAggregation
{
    public const INTERVAL_MINUTE = 'minute';

    public const INTERVAL_HOUR = 'hour';

    public const INTERVAL_DAY = 'day';

    public const INTERVAL_WEEK = 'week';

    public const INTERVAL_MONTH = 'month';

    public const INTERVAL_QUARTER = 'quarter';

    public const INTERVAL_YEAR = 'year';

    private string $interval;

    private ?string $format;

    private ?string $timeZone;

    private ?SortingContract $sorting;

    private ?AggregationContract $aggregation;

    public function __construct(
        string $name,
        string $field,
        string $interval,
        ?string $format = null,
        ?string $timeZone = null,
        ?SortingContract $sorting = null,
        ?AggregationContract $aggregation = null
    ) {
        parent::__construct($name, $field);
        $this->interval = $interval;
        $this->format = $format;
        $this->timeZone = $timeZone;
        $this->sorting = $sorting;
        $this->aggregation = $aggregation;
    }

    public function getInterval(): string
    {
        return $this->interval;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function getSorting(): ?SortingContract
    {
        return $this->sorting;
    }

    public function getAggregation(): ?AggregationContract
    {
        return $this->aggregation;
    }
}
