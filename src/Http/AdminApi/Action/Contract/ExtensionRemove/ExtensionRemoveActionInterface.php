<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRemove;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException;

interface ExtensionRemoveActionInterface
{
    /**
     * Removes the referenced extension.
     *
     * @throws PluginNotFoundException if the referenced extension is not found
     * @throws \Throwable
     */
    public function removeExtension(ExtensionRemovePayload $payload): void;
}
