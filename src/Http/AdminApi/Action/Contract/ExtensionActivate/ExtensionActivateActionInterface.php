<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionActivate;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExtensionNotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException;

interface ExtensionActivateActionInterface
{
    /**
     * Activates the referenced extension.
     *
     * @throws ExtensionNotFoundException if the referenced app is not found
     * @throws PluginNotFoundException    if the referenced plugin is not found
     * @throws \Throwable
     */
    public function activateExtension(ExtensionActivatePayload $payload): void;
}
