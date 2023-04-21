<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpdate;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\AbstractExtensionPayload;

final class ExtensionUpdatePayload extends AbstractExtensionPayload
{
    private bool $allowNewPermissions = false;

    public function isAllowNewPermissions(): bool
    {
        return $this->allowNewPermissions;
    }

    public function withAllowNewPermissions(bool $allowNewPermissions): self
    {
        $that = clone $this;
        $that->allowNewPermissions = $allowNewPermissions;

        return $that;
    }
}
