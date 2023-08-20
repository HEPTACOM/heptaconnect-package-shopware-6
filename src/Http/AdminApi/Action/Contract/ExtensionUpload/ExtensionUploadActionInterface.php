<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNoPluginFoundInZipException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnknownError;

interface ExtensionUploadActionInterface
{
    /**
     * Uploads a ZIP file and tries to provide it as extension.
     *
     * @throws PluginNoPluginFoundInZipException if the given ZIP file does not contain a detectable plugin
     * @throws UnknownError                      if the upload fails before reaching the plugin service
     * @throws \Throwable
     */
    public function uploadExtension(ExtensionUploadPayload $payload): void;
}
