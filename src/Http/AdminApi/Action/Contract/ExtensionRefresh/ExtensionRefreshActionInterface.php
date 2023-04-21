<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRefresh;

interface ExtensionRefreshActionInterface
{
    /**
     * Refresh the data in the extension listing.
     */
    public function refreshExtensions(ExtensionRefreshParams $params): void;
}
