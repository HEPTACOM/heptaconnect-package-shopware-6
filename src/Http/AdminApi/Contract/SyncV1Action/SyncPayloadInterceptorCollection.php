<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncV1Action;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<SyncPayloadInterceptorInterface>
 */
final class SyncPayloadInterceptorCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return SyncPayloadInterceptorInterface::class;
    }
}
