<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncV1Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SyncV1\SyncPayload;

interface SyncPayloadInterceptorInterface
{
    /**
     * Is allowed to alter the operations either by removing, adding new and altering contents of entries.
     * Is allowed to alter the expected packages.
     * The return value can be an altered version of the input argument.
     */
    public function intercept(SyncPayload $payload): SyncPayload;
}
