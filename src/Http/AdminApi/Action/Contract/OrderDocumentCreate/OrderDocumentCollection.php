<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<OrderDocument>
 */
final class OrderDocumentCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return OrderDocument::class;
    }
}
