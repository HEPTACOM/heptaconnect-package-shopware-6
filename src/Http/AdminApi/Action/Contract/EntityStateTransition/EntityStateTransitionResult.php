<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\EntityStateTransition;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity;

final class EntityStateTransitionResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private Entity $state;

    public function __construct(Entity $state)
    {
        $this->attachments = new AttachmentCollection();
        $this->state = $state;
    }

    public function getState(): Entity
    {
        return $this->state;
    }
}
