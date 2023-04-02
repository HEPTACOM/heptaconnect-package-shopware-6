<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost;

interface SystemConfigPostActionInterface
{
    /**
     * Sets multiple values in the system configuration for the given sales channel.
     * When none is given, the system configuration is applied system-wide.
     *
     * @throws \Throwable
     */
    public function postSystemConfig(SystemConfigPostPayload $payload): void;
}
