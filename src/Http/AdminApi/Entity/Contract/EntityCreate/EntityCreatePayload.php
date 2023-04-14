<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Support\ExpectedPackagesAwareTrait;

final class EntityCreatePayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $entityName;

    private array $payload;

    public function __construct(string $entityName, array $payload)
    {
        $this->entityName = $entityName;
        $this->payload = $payload;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function withEntityName(string $entityName): self
    {
        $that = clone $this;
        $that->entityName = $entityName;

        return $that;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function withPayload(array $payload): self
    {
        $that = clone $this;
        $that->payload = $payload;

        return $that;
    }
}
