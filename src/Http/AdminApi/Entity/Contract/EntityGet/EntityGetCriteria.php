<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Support\ExpectedPackagesAwareTrait;

final class EntityGetCriteria implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $entityName;

    private string $id;

    private Criteria $criteria;

    public function __construct(string $entityName, string $id, Criteria $criteria)
    {
        $this->entityName = $entityName;
        $this->id = $id;
        $this->criteria = $criteria;
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

    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    public function withCriteria(Criteria $criteria): self
    {
        $that = clone $this;
        $that->criteria = $criteria;

        return $that;
    }
}
