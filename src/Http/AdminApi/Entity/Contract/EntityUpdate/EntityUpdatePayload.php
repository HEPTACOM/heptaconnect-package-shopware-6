<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

final class EntityUpdatePayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $entityName;

    private string $id;

    private array $payload;

    public function __construct(string $entityName, string $id, array $payload)
    {
        $this->attachments = new AttachmentCollection();
        $this->entityName = $entityName;
        $this->id = $id;
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

    public function getId(): string
    {
        return $this->id;
    }

    public function withId(string $id): self
    {
        $that = clone $this;
        $that->id = $id;

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
