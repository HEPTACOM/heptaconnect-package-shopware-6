<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet;

interface SystemConfigGetActionInterface
{
    /**
     * Gets system configuration values of a domain for the given sales channel.
     * When none is given, the system configuration is requested system-wide.
     *
     * @throws \Throwable
     *
     * @returns array<string, mixed>
     */
    public function getSystemConfig(SystemConfigGetCriteria $criteria): SystemConfigGetResult;
}
