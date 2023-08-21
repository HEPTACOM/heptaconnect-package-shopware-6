<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaDuplicatedFileNameException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaFileTypeNotSupportedException;

interface MediaUploadActionInterface
{
    /**
     * Upload media by URL or a local file.
     *
     * @throws MediaDuplicatedFileNameException   if the given filename is already in use
     * @throws MediaFileTypeNotSupportedException if the extension is not allowed
     * @throws \Throwable
     */
    public function uploadMedia(AbstractMediaUploadPayload $payload): void;
}
