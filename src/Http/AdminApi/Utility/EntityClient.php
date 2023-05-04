<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\FilterAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResult;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSorting;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterContract;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityDelete\EntityDeleteActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityDelete\EntityDeleteCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\ResourceNotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Support\LetterCase;

/**
 * Facade to entity action methods with a reduced set of arguments.
 * If you want to intercept a process, use the action services.
 */
final class EntityClient
{
    private EntitySearchActionInterface $searchAction;

    private EntitySearchIdActionInterface $searchIdAction;

    private EntityCreateActionInterface $createAction;

    private EntityGetActionInterface $getAction;

    private EntityUpdateActionInterface $updateAction;

    private EntityDeleteActionInterface $deleteAction;

    public function __construct(
        EntitySearchActionInterface $searchAction,
        EntitySearchIdActionInterface $searchIdAction,
        EntityCreateActionInterface $createAction,
        EntityGetActionInterface $getAction,
        EntityUpdateActionInterface $updateAction,
        EntityDeleteActionInterface $deleteAction
    ) {
        $this->searchAction = $searchAction;
        $this->searchIdAction = $searchIdAction;
        $this->createAction = $createAction;
        $this->getAction = $getAction;
        $this->updateAction = $updateAction;
        $this->deleteAction = $deleteAction;
    }

    /**
     * Searches for entities.
     *
     * @throws \Throwable
     */
    public function search(string $entityName, ?Criteria $criteria = null): EntityCollection
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);
        $criteria ??= new Criteria();

        return $this->searchAction->search(new EntitySearchCriteria($entityName, $criteria))->getData();
    }

    /**
     * Searches for entity ids.
     *
     * @throws \Throwable
     *
     * @returns list<string>|list<array<string, string>>
     */
    public function searchIds(string $entityName, ?Criteria $criteria = null): array
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);
        $criteria ??= new Criteria();

        return $this->searchIdAction->searchIds(new EntitySearchIdCriteria($entityName, $criteria))->getData();
    }

    /**
     * Counts the entity by the given criteria.
     *
     * @throws \Throwable
     */
    public function count(string $entityName, ?Criteria $criteria = null): int
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);
        $criteria ??= new Criteria();
        $criteria = $criteria->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_EXACT);
        $criteria = $criteria->withLimit(1);

        return $this->searchIdAction->searchIds(new EntitySearchIdCriteria($entityName, $criteria))->getTotal();
    }

    public function aggregate(string $entityName, AggregationContract $aggregation, ?FilterContract $filter = null): AggregationResult
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);
        $criteria = new Criteria();
        $criteria = $criteria->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NONE);
        $criteria = $criteria->withLimit(1);
        $inAggregation = $aggregation;

        if ($aggregation instanceof TermsAggregation && $aggregation->getSorting() === null) {
            $aggregation = new TermsAggregation(
                $aggregation->getName(),
                $aggregation->getField(),
                new FieldSorting($aggregation->getField()),
                $aggregation->getAggregation()
            );
        }

        if ($filter !== null) {
            $aggregation = new FilterAggregation('filter' . $aggregation->getName(), $aggregation, new FilterCollection([
                $filter,
            ]));
        }

        $criteria = $criteria->withAddedAggregation($aggregation);

        return $this->searchAction->search(new EntitySearchCriteria($entityName, $criteria))->getAggregations()[$inAggregation->getName()];
    }

    public function groupFieldByField(string $entityName, string $fieldKey, string $fieldValue, ?FilterContract $filter = null): array
    {
        $response = $this->aggregate(
            $entityName,
            new TermsAggregation('key', $fieldKey, null, new TermsAggregation('value', $fieldValue)),
            $filter
        );
        $result = [];

        foreach ($response->buckets as $bucket) {
            $result[$bucket['key']] = $bucket['value']['buckets'][0]['key'];
        }

        return $result;
    }

    /**
     * Iterates over all entities by the given criteria.
     *
     * @throws \Throwable
     *
     * @return iterable<Entity>
     */
    public function iterate(string $entityName, ?Criteria $criteria = null): iterable
    {
        $criteria ??= new Criteria();
        $criteria = $criteria->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NEXT_PAGES);
        $criteria = $criteria->withLimit($criteria->getLimit() ?? 500);
        $criteria = $criteria->withPage(1);

        if ($criteria->getSort() === null) {
            $criteria = $criteria->withFieldSort('id');
        }

        $index = 0;
        $entityName = LetterCase::fromUnderscoreToDash($entityName);

        do {
            $searchResult = $this->searchAction->search(new EntitySearchCriteria($entityName, $criteria));

            foreach ($searchResult->getData() as $data) {
                yield $index++ => $data;
            }

            $criteria = $criteria->withPage(($criteria->getPage() ?? 0) + 1);
        } while (\count($searchResult->getData()) < $searchResult->getTotal());
    }

    /**
     * Iterates over all entities by the given criteria.
     *
     * @throws \Throwable
     *
     * @return iterable<string|list<string>>
     */
    public function iterateIds(string $entityName, ?Criteria $criteria = null): iterable
    {
        $criteria ??= new Criteria();
        $criteria = $criteria->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NEXT_PAGES);
        $criteria = $criteria->withLimit($criteria->getLimit() ?? 500);
        $criteria = $criteria->withPage(1);

        if ($criteria->getSort() === null) {
            $criteria = $criteria->withFieldSort('id');
        }

        $index = 0;
        $entityName = LetterCase::fromUnderscoreToDash($entityName);

        do {
            $searchResult = $this->searchIdAction->searchIds(new EntitySearchIdCriteria($entityName, $criteria));

            foreach ($searchResult->getData() as $data) {
                yield $index++ => $data;
            }

            $criteria = $criteria->withPage(($criteria->getPage() ?? 0) + 1);
        } while (\count($searchResult->getData()) < $searchResult->getTotal());
    }

    /**
     * Reads the first id matched from the condition.
     *
     * @return string|string[]|null
     */
    public function getFirstId(string $entityName, FilterContract $filter)
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);
        $criteria = (new Criteria())->withLimit(1)->withPage(1)->withAndFilter($filter);
        $searchResult = $this->searchIdAction->searchIds(new EntitySearchIdCriteria($entityName, $criteria));

        return $searchResult->getData()[0] ?? null;
    }

    /**
     * Checks, whether the entity exists with the given id.
     */
    public function exists(string $entityName, string $id): bool
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);
        $criteria = (new Criteria())->withLimit(1)->withPage(1)->withIds([$id]);
        $searchResult = $this->searchIdAction->searchIds(new EntitySearchIdCriteria($entityName, $criteria));

        return $searchResult->getTotal() > 0;
    }

    /**
     * Creates the entity and returns the created id.
     *
     * @throws \Throwable
     */
    public function create(string $entityName, array $payload): string
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);

        return $this->createAction->create(new EntityCreatePayload($entityName, $payload))->getId();
    }

    /**
     * Reads and returns the entity.
     *
     * @param string[] $associations
     *
     * @throws \Throwable
     */
    public function get(string $entityName, string $id, ?array $associations = null): Entity
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);
        $criteria = new Criteria();

        if ($associations !== null) {
            $criteria = $criteria->withAddedAssociations(new StringCollection($associations));
        }

        return $this->getAction->get(new EntityGetCriteria($entityName, $id, $criteria))->getEntity();
    }

    /**
     * Updates the entity and returns the created id.
     *
     * @throws \Throwable
     */
    public function update(string $entityName, array $payload): void
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);

        $this->updateAction->update(new EntityUpdatePayload($entityName, $payload['id'], $payload));
    }

    /**
     * Deletes the entity.
     *
     * @throws \Throwable
     */
    public function delete(string $entityName, string $id): void
    {
        $entityName = LetterCase::fromUnderscoreToDash($entityName);

        try {
            $this->deleteAction->delete(new EntityDeleteCriteria($entityName, $id));
        } catch (ResourceNotFoundException $e) {
            // this is ok, because it is not in the storage anymore
        }
    }
}
