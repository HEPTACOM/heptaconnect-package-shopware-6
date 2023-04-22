<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection;

final class Criteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * no total count will be selected. Should be used if no pagination required (fastest)
     */
    public const TOTAL_COUNT_MODE_NONE = 0;

    /**
     * exact total count will be selected. Should be used if an exact pagination is required (slow)
     */
    public const TOTAL_COUNT_MODE_EXACT = 1;

    /**
     * fetches limit * 5 + 1. Should be used if pagination can work with "next page exists" (fast)
     */
    public const TOTAL_COUNT_MODE_NEXT_PAGES = 2;

    private ?int $limit = null;

    private ?int $totalCountMode = null;

    private ?int $page = null;

    /**
     * @var list<string>|list<list<string>>|null
     */
    private ?array $ids = null;

    private ?string $term = null;

    private ?TaggedStringCollection $includes = null;

    private ?FieldSortingCollection $sort = null;

    private ?StringCollection $grouping = null;

    private ?AggregationCollection $aggregations = null;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function withLimit(?int $limit): self
    {
        $that = clone $this;
        $that->limit = $limit;

        return $that;
    }

    public function getTotalCountMode(): ?int
    {
        return $this->totalCountMode;
    }

    public function withTotalCountMode(?int $totalCountMode): self
    {
        $that = clone $this;
        $that->totalCountMode = $totalCountMode;

        return $that;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function withPage(?int $page): self
    {
        $that = clone $this;
        $that->page = $page;

        return $that;
    }

    /**
     * @return list<string>|list<list<string>>|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param list<string>|list<list<string>>|null $ids
     */
    public function withIds(?array $ids): self
    {
        $that = clone $this;
        $that->ids = $ids;

        return $that;
    }

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function withTerm(?string $term): self
    {
        $that = clone $this;
        $that->term = $term;

        return $that;
    }

    public function getIncludes(): ?TaggedStringCollection
    {
        return $this->includes === null ? null : new TaggedStringCollection($this->includes);
    }

    public function withIncludes(?TaggedStringCollection $includes): self
    {
        $that = clone $this;
        $that->includes = $includes;

        return $that;
    }

    /**
     * @param iterable<string>|null $includes
     */
    public function withAddedIncludes(string $entity, ?iterable $includes): self
    {
        $that = clone $this;

        if ($includes === null) {
            if ($that->includes !== null) {
                unset($that->includes[$entity]);
            }
        } else {
            $tagged = $that->includes;

            if ($tagged === null) {
                $tagged = new TaggedStringCollection();
                $that->includes = $tagged;
            }

            $tagged[$entity]->getCollection()->push($includes);
        }

        return $that;
    }

    public function getSort(): ?FieldSortingCollection
    {
        return $this->sort;
    }

    public function withSort(?FieldSortingCollection $sort): self
    {
        $that = clone $this;
        $that->sort = $sort;

        return $that;
    }

    public function withFieldSort(string $field, string $direction = FieldSorting::ASCENDING, bool $naturalSorting = false): self
    {
        $sort = $this->sort;

        if ($sort === null) {
            $sort = new FieldSortingCollection();
        }

        $sort->push([new FieldSorting($field, $direction, $naturalSorting)]);

        return $this->withSort($sort);
    }

    public function withoutFieldSort(string $field): self
    {
        $sort = $this->sort;

        if ($sort !== null) {
            $sort = new FieldSortingCollection($sort->filter(
                static fn (FieldSorting $sorting): bool => $sorting->getField() !== $field
            ));

            if ($sort->isEmpty()) {
                $sort = null;
            }
        }

        return $this->withSort($sort);
    }

    public function getGrouping(): ?StringCollection
    {
        return $this->grouping;
    }

    public function withGrouping(?StringCollection $grouping): self
    {
        $that = clone $this;
        $that->grouping = $grouping;

        return $that;
    }

    public function withAddedGroupField(string $groupField): self
    {
        $grouping = $this->getGrouping() ?? new StringCollection();

        $grouping->push([$groupField]);

        return $this->withGrouping($grouping);
    }

    public function withoutGroupField(string $groupField): self
    {
        $grouping = $this->getGrouping() ?? new StringCollection();
        $grouping = new StringCollection($grouping->filter(
            static fn (string $field): bool => $field !== $groupField
        ));

        if ($grouping->isEmpty()) {
            $grouping = null;
        }

        return $this->withGrouping($grouping);
    }

    public function getAggregations(): ?AggregationCollection
    {
        return $this->aggregations;
    }

    public function withAggregations(?AggregationCollection $aggregations): self
    {
        $that = clone $this;
        $that->aggregations = $aggregations;

        return $that;
    }

    public function withAddedAggregation(AggregationContract $aggregation): self
    {
        $aggregations = new AggregationCollection($this->getAggregations() ?? []);
        $aggregations->push([$aggregation]);

        return $this->withAggregations($aggregations);
    }

    public function withoutAggregation(string $aggregationName): self
    {
        $aggregations = new AggregationCollection($this->getAggregations() ?? []);
        unset($aggregations[$aggregationName]);

        if ($aggregations->isEmpty()) {
            $aggregations = null;
        }

        return $this->withAggregations($aggregations);
    }
}
