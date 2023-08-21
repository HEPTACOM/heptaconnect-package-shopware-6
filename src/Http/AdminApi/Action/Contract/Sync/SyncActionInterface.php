<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception\SyncResultException;

/**
 * This is the second iteration of the Sync API provided since the first version of Shopware 6.5.
 *
 * @see \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SyncV1\SyncActionInterface for the previous version.
 */
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
