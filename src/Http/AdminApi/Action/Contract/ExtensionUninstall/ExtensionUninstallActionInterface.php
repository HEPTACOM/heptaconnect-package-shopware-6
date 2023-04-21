<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException;

interface ExtensionUninstallActionInterface
{
    /**
     * Uninstalls the referenced extension.
     *
     * @throws PluginNotFoundException if the referenced extension is not in the shop
     * @throws \Throwable
     */
    public function uninstallExtension(ExtensionUninstallPayload $payload): void;
}
