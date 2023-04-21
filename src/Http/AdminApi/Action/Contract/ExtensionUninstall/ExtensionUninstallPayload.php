<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\AbstractExtensionPayload;

final class ExtensionUninstallPayload extends AbstractExtensionPayload
{
    private bool $keepUserData = true;

    public function isKeepUserData(): bool
    {
        return $this->keepUserData;
    }

    public function withKeepUserData(bool $keepUserData): self
    {
        $that = clone $this;
        $that->keepUserData = $keepUserData;

        return $that;
    }
}
