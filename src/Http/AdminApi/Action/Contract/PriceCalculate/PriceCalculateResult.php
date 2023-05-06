<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity;

final class PriceCalculateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private Entity $price;

    public function __construct(Entity $price)
    {
        $this->attachments = new AttachmentCollection();
        $this->price = $price;
    }

    public function getPrice(): Entity
    {
        return $this->price;
    }
}
