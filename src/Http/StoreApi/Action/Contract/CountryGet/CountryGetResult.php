<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection;

final class CountryGetResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private EntityCollection $elements;

    private AggregationResultCollection $aggregations;

    private ?int $total;

    private int $page;

    private ?int $limit;

    private StringCollection $states;

    public function __construct(
        EntityCollection $elements,
        AggregationResultCollection $aggregations,
        ?int $total,
        int $page,
        ?int $limit,
        StringCollection $states
    ) {
        $this->attachments = new AttachmentCollection();
        $this->elements = $elements;
        $this->aggregations = $aggregations;
        $this->total = $total;
        $this->page = $page;
        $this->limit = $limit;
        $this->states = $states;
    }

    public function getElements(): EntityCollection
    {
        return $this->elements;
    }

    public function getAggregations(): AggregationResultCollection
    {
        return $this->aggregations;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getStates(): StringCollection
    {
        return $this->states;
    }
}
