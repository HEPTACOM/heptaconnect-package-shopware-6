<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Psr\Http\Message\StreamInterface;

final class OrderDocumentGetResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private StreamInterface $document;

    private string $mimeType;

    private ?string $filename;

    public function __construct(StreamInterface $document, string $mimeType, ?string $filename)
    {
        $this->attachments = new AttachmentCollection();
        $this->document = $document;
        $this->mimeType = $mimeType;
        $this->filename = $filename;
    }

    public function getDocument(): StreamInterface
    {
        return $this->document;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }
}
