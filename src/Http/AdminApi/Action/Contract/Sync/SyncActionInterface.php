<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

interface SyncActionInterface
{
    /**
     * Perform different write operations in a batch.
     *
     * @throws \Throwable
     */
    public function sync(SyncPayload $payload): SyncResult;
}
