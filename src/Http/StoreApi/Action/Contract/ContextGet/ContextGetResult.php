<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity;

final class ContextGetResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private Entity $context;

    public function __construct(Entity $context)
    {
        $this->attachments = new AttachmentCollection();
        $this->context = $context;
    }

    public function getContext(): Entity
    {
        return $this->context;
    }
}
