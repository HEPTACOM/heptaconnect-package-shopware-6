<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ContextTokenAwareTrait;

final class CountryGetCriteria implements AttachmentAwareInterface, ContextTokenAwareInterface
{
    use AttachmentAwareTrait;
    use ContextTokenAwareTrait;

    private Criteria $criteria;

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
        $this->criteria = new Criteria();
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
