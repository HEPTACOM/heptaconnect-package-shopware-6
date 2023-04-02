<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info;

interface InfoActionInterface
{
    /**
     * Gets the Shopware system version.
     *
     * @throws \Throwable
     */
    public function getInfo(InfoParams $params): InfoResult;
}
