<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpdate;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExtensionInstallException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException;

interface ExtensionUpdateActionInterface
{
    /**
     * Performs update steps for the referenced extension.
     *
     * @throws ExtensionInstallException if the referenced extension is not a valid reference
     * @throws PluginNotFoundException   if the referenced extension is not in the shop
     * @throws \Throwable
     */
    public function updateExtension(ExtensionUpdatePayload $payload): void;
}
