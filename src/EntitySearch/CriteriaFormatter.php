<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch;

use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\AverageAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\CountAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\EntityAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\FilterAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\HistogramAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\MaximumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\MinimumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\StatisticsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\SumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\CountSorting;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\CriteriaFormatterInterface;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSorting;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\AbstractNestedFilters;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\ContainsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsAnyFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\NotFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\PrefixFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\RangeFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\SuffixFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterContract;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\ScoreQuery;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\ScoreQueryCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\SortingCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\SortingContract;

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
        $filter = $criteria->getFilter();
        $postFilter = $criteria->getPostFilter();
        $queries = $criteria->getQueries();
        $associations = $criteria->getAssociations();

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

        if ($filter !== null) {
            $result['filter'] = $this->getFiltersValues($filter);
        }

        if ($postFilter !== null) {
            $result['post-filter'] = $this->getFiltersValues($postFilter);
        }

        if ($queries !== null) {
            $result['query'] = $this->getQueriesValues($queries);
        }

        if ($associations !== null) {
            $result['associations'] = $this->getAssociationsValues($associations);
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
    private function getSortValues(SortingCollection $sort): array
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
    private function getFiltersValues(FilterCollection $filters): array
    {
        $result = [];

        foreach ($filters as $filter) {
            $result[] = $this->getFilterValues($filter);
        }

        return $result;
    }

    /**
     * @throws \UnexpectedValueException
     */
    private function getFilterValues(FilterContract $filter): array
    {
        if ($filter instanceof EqualsFilter) {
            return [
                'type' => 'equals',
                'field' => $filter->getField(),
                'value' => $filter->getValue(),
            ];
        }

        if ($filter instanceof ContainsFilter) {
            return [
                'type' => 'contains',
                'field' => $filter->getField(),
                'value' => $filter->getValue(),
            ];
        }

        if ($filter instanceof PrefixFilter) {
            return [
                'type' => 'prefix',
                'field' => $filter->getField(),
                'value' => $filter->getValue(),
            ];
        }

        if ($filter instanceof SuffixFilter) {
            return [
                'type' => 'suffix',
                'field' => $filter->getField(),
                'value' => $filter->getValue(),
            ];
        }

        if ($filter instanceof EqualsAnyFilter) {
            return [
                'type' => 'equalsAny',
                'field' => $filter->getField(),
                'value' => $filter->getValues(),
            ];
        }

        if ($filter instanceof RangeFilter) {
            return [
                'type' => 'range',
                'field' => $filter->getField(),
                'parameters' => $filter->getConstraints(),
            ];
        }

        if ($filter instanceof NotFilter) {
            return [
                'type' => 'not',
                'queries' => $this->getFiltersValues($filter->getFilters()),
                'operator' => $filter->getOperator(),
            ];
        }

        if ($filter instanceof AbstractNestedFilters) {
            return [
                'type' => 'multi',
                'queries' => $this->getFiltersValues($filter->getFilters()),
                'operator' => $filter->getOperator(),
            ];
        }

        throw new \UnexpectedValueException('Type of $filter is not supported', 1682167002);
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

        if ($aggregation instanceof FilterAggregation) {
            return [
                'name' => $aggregation->getName(),
                'type' => 'filter',
                'filter' => $this->getFiltersValues($aggregation->getFilters()),
                'aggregation' => $this->getAggregationValues($aggregation->getAggregation()),
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

    private function getFieldSortValues(SortingContract $sort): array
    {
        if ($sort instanceof CountSorting) {
            return [
                'type' => 'count',
                'field' => $sort->getField(),
                'order' => $sort->getDirection(),
            ];
        }

        if ($sort instanceof FieldSorting) {
            return [
                'field' => $sort->getField(),
                'order' => $sort->getDirection(),
                'naturalSorting' => $sort->isNaturalSorting(),
            ];
        }

        throw new \UnexpectedValueException('Type of $sort is not supported', 1682167001);
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

    private function getQueriesValues(ScoreQueryCollection $queries): array
    {
        $result = [];

        foreach ($queries as $query) {
            $result[] = $this->getQueryValues($query);
        }

        return $result;
    }

    private function getQueryValues(ScoreQuery $query): array
    {
        $result = [
            'query' => $this->getFilterValues($query->getFilter()),
        ];
        $score = $query->getScore();
        $scoreField = $query->getScoreField();

        if ($score !== null) {
            $result['score'] = $score;
        }

        if ($scoreField !== null) {
            $result['scoreField'] = $scoreField;
        }

        return $result;
    }

    /**
     * @param array<string, Criteria> $associations
     */
    private function getAssociationsValues(array $associations): array
    {
        $result = [];

        foreach ($associations as $associationName => $criteria) {
            $result[$associationName] = $this->formatCriteria($criteria);
        }

        return $result;
    }
}
