<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

abstract class AbstractEntitySearchCriteria implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $entityName;

    private Criteria $criteria;

    public function __construct(string $entityName, Criteria $criteria)
    {
        $this->attachments = new AttachmentCollection();
        $this->entityName = $entityName;
        $this->criteria = $criteria;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * @return static
     */
    public function withEntityName(string $entityName): self
    {
        $that = clone $this;
        $that->entityName = $entityName;

        return $that;
    }

    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    /**
     * @return static
     */
    public function withCriteria(Criteria $criteria): self
    {
        $that = clone $this;
        $that->criteria = $criteria;

        return $that;
    }
}
