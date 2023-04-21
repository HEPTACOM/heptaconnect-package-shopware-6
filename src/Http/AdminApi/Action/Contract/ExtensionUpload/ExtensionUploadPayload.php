<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;
use Psr\Http\Message\StreamInterface;

final class ExtensionUploadPayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $zipFileName;

    private StreamInterface $zipFileStream;

    public function __construct(string $zipFileName, StreamInterface $zipFileStream)
    {
        $this->attachments = new AttachmentCollection();
        $this->zipFileName = $zipFileName;
        $this->zipFileStream = $zipFileStream;
    }

    public function getZipFileName(): string
    {
        return $this->zipFileName;
    }

    public function withZipFileName(string $zipFileName): self
    {
        $that = clone $this;
        $that->zipFileName = $zipFileName;

        return $that;
    }

    public function getZipFileStream(): StreamInterface
    {
        return $this->zipFileStream;
    }

    public function withZipFileStream(StreamInterface $zipFileStream): self
    {
        $that = clone $this;
        $that->zipFileStream = $zipFileStream;

        return $that;
    }
}
