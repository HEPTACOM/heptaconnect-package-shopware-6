<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class StorePluginSearchResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private StorePluginCollection $items;

    private ?int $total;

    public function __construct(StorePluginCollection $items, ?int $total)
    {
        $this->attachments = new AttachmentCollection();
        $this->items = $items;
        $this->total = $total;
    }

    public function getItems(): StorePluginCollection
    {
        return $this->items;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }
}
