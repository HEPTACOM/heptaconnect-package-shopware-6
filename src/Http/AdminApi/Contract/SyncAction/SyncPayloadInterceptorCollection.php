<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

final class SyncPayloadInterceptorCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return SyncPayloadInterceptorInterface::class;
    }
}
