<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion;

interface InfoVersionActionInterface
{
    /**
     * Gets the Shopware system version.
     *
     * @throws \Throwable
     */
    public function getVersion(InfoVersionParams $params): InfoVersionResult;
}
