<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception\SyncResultException;

interface SyncActionInterface
{
    /**
     * Perform different write operations in a batch.
     *
     * @throws \Throwable
     * @throws SyncResultException
     */
    public function sync(SyncPayload $payload): SyncResult;
}
