<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch;

use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\AverageAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\CountAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\EntityAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\HistogramAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\MaximumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\MinimumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\StatisticsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\SumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\CriteriaFormatterInterface;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSortingCollection;

final class CriteriaFormatter implements CriteriaFormatterInterface
{
    public function formatCriteria(Criteria $criteria): array
    {
        $result = [];
        $limit = $criteria->getLimit();
        $totalCountMode = $criteria->getTotalCountMode();
        $page = $criteria->getPage();
        $ids = $criteria->getIds();
        $term = $criteria->getTerm();
        $includes = $criteria->getIncludes();
        $sort = $criteria->getSort();
        $grouping = $criteria->getGrouping();
        $aggregations = $criteria->getAggregations();

        if ($limit !== null) {
            $result['limit'] = $limit;
        }

        if ($totalCountMode !== null) {
            $result['total-count-mode'] = $totalCountMode;
        }

        if ($page !== null) {
            $result['page'] = $page;
        }

        if ($ids !== null) {
            $result['ids'] = $ids;
        }

        if ($term !== null) {
            $result['term'] = $term;
        }

        if ($includes !== null) {
            $result['includes'] = $this->getIncludeValues($includes);
        }

        if ($sort !== null) {
            $result['sort'] = $this->getSortValues($sort);
        }

        if ($grouping !== null) {
            $result['grouping'] = $grouping->asArray();
        }

        if ($aggregations !== null) {
            $result['aggregations'] = $this->getAggregationsValues($aggregations);
        }

        return $result;
    }

    /**
     * @return array<string, list<string>>
     */
    private function getIncludeValues(TaggedStringCollection $includes): array
    {
        $result = [];

        /** @var TagItem<string> $include */
        foreach ($includes as $include) {
            $result[$include->getTag()] = $include->getCollection()->asArray();
        }

        return $result;
    }

    /**
     * @return list<array{field: string, order: string, naturalSorting: bool}>
     */
    private function getSortValues(FieldSortingCollection $sort): array
    {
        $result = [];

        foreach ($sort as $instruction) {
            $result[] = $this->getFieldSortValues($instruction);
        }

        return $result;
    }

    /**
     * @throws \UnexpectedValueException
     */
    private function getAggregationsValues(AggregationCollection $aggregations): array
    {
        $result = [];

        foreach ($aggregations as $aggregation) {
            $result[] = $this->getAggregationValues($aggregation);
        }

        return $result;
    }

    /**
     * @throws \UnexpectedValueException
     */
    private function getAggregationValues(AggregationContract $aggregation): array
    {
        if ($aggregation instanceof AverageAggregation) {
            return [
                'name' => $aggregation->getName(),
                'type' => 'avg',
                'field' => $aggregation->getField(),
            ];
        }

        if ($aggregation instanceof MaximumAggregation) {
            return [
                'name' => $aggregation->getName(),
                'type' => 'max',
                'field' => $aggregation->getField(),
            ];
        }

        if ($aggregation instanceof MinimumAggregation) {
            return [
                'name' => $aggregation->getName(),
                'type' => 'min',
                'field' => $aggregation->getField(),
            ];
        }

        if ($aggregation instanceof StatisticsAggregation) {
            return [
                'name' => $aggregation->getName(),
                'type' => 'stats',
                'field' => $aggregation->getField(),
            ];
        }

        if ($aggregation instanceof SumAggregation) {
            return [
                'name' => $aggregation->getName(),
                'type' => 'sum',
                'field' => $aggregation->getField(),
            ];
        }

        if ($aggregation instanceof CountAggregation) {
            return [
                'name' => $aggregation->getName(),
                'type' => 'count',
                'field' => $aggregation->getField(),
            ];
        }

        if ($aggregation instanceof EntityAggregation) {
            return [
                'name' => $aggregation->getName(),
                'type' => 'entity',
                'field' => $aggregation->getField(),
                'definition' => $aggregation->getEntityName(),
            ];
        }

        if ($aggregation instanceof HistogramAggregation) {
            return $this->getHistogramAggregationValues($aggregation);
        }

        if ($aggregation instanceof TermsAggregation) {
            return $this->getTermsAggregationValues($aggregation);
        }

        throw new \UnexpectedValueException('Type of $aggregation is not supported', 1682167000);
    }

    private function getFieldSortValues($instruction): array
    {
        return [
            'field' => $instruction->getField(),
            'order' => $instruction->getDirection(),
            'naturalSorting' => $instruction->isNaturalSorting(),
        ];
    }

    private function getHistogramAggregationValues(HistogramAggregation $aggregation): array
    {
        $result = [
            'name' => $aggregation->getName(),
            'type' => 'histogram',
            'interval' => $aggregation->getInterval(),
            'format' => $aggregation->getFormat(),
            'field' => $aggregation->getField(),
            'timeZone' => $aggregation->getTimeZone(),
        ];

        $sorting = $aggregation->getSorting();

        if ($sorting !== null) {
            $result['sort'] = $this->getFieldSortValues($sorting);
        }

        $innerAggregation = $aggregation->getAggregation();

        if ($innerAggregation !== null) {
            $result['aggregation'] = $this->getAggregationValues($innerAggregation);
        }

        return $result;
    }

    private function getTermsAggregationValues(TermsAggregation $aggregation): array
    {
        $result = [
            'name' => $aggregation->getName(),
            'type' => 'terms',
            'field' => $aggregation->getField(),
        ];

        $sorting = $aggregation->getSorting();

        if ($sorting !== null) {
            $result['sort'] = $this->getFieldSortValues($sorting);
        }

        $innerAggregation = $aggregation->getAggregation();

        if ($innerAggregation !== null) {
            $result['aggregation'] = $this->getAggregationValues($innerAggregation);
        }

        return $result;
    }
}
