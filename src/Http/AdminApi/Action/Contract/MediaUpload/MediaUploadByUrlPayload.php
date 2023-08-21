<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload;

final class MediaUploadByUrlPayload extends AbstractMediaUploadPayload
{
    private string $url;

    public function __construct(string $url, string $mediaId, string $extension, ?string $fileName = null)
    {
        parent::__construct($mediaId, $extension, $fileName);
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return static
     */
    public function withUrl(string $url): self
    {
        $that = clone $this;
        $that->url = $url;

        return $that;
    }
}
