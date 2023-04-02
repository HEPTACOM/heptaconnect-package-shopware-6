<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch;

interface SystemConfigBatchActionInterface
{
    /**
     * Sets multiple values in the system configuration for the given sales channel.
     * When 'null' is given, the system configuration is applied system-wide.
     *
     * @throws \Throwable
     */
    public function batchSystemConfig(SystemConfigBatchPayload $payload): void;
}
