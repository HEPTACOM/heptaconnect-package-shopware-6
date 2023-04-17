<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class EntityCreateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $entityName;

    private string $id;

    public function __construct(string $entityName, string $id)
    {
        $this->attachments = new AttachmentCollection();
        $this->entityName = $entityName;
        $this->id = $id;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
