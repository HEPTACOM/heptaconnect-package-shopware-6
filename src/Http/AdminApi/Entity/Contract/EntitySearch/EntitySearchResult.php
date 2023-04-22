<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection;

final class EntitySearchResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private EntityCollection $data;

    private ?int $total;

    private AggregationResultCollection $aggregations;

    public function __construct(EntityCollection $data, ?int $total, AggregationResultCollection $aggregations)
    {
        $this->attachments = new AttachmentCollection();
        $this->data = $data;
        $this->total = $total;
        $this->aggregations = $aggregations;
    }

    public function getData(): EntityCollection
    {
        return $this->data;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function getAggregations(): AggregationResultCollection
    {
        return $this->aggregations;
    }
}
