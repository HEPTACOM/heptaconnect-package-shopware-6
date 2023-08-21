<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SyncV1;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception\SyncResultException;

/**
 * This is the version 1 of the Sync API provided until the last version of Shopware 6.4.
 *
 * @see \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncActionInterface for latest version.
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
