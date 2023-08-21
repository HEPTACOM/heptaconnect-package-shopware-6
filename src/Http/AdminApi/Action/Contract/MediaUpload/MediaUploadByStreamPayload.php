<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload;

use Psr\Http\Message\StreamInterface;

final class MediaUploadByStreamPayload extends AbstractMediaUploadPayload
{
    private StreamInterface $stream;

    public function __construct(StreamInterface $stream, string $mediaId, string $extension, ?string $fileName = null)
    {
        parent::__construct($mediaId, $extension, $fileName);
        $this->stream = $stream;
    }

    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    /**
     * @return static
     */
    public function withStream(StreamInterface $stream): self
    {
        $that = clone $this;
        $that->stream = $stream;

        return $that;
    }
}
