<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class EntityUpdateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $entityName;

    private string $id;

    public function __construct(string $entityName, string $id)
    {
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
