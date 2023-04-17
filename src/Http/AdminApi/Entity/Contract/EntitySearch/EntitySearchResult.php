<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Contract\EntityCollection;

final class EntitySearchResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private EntityCollection $data;

    private ?int $total;

    public function __construct(EntityCollection $data, ?int $total)
    {
        $this->attachments = new AttachmentCollection();
        $this->data = $data;
        $this->total = $total;
    }

    public function getData(): EntityCollection
    {
        return $this->data;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }
}
