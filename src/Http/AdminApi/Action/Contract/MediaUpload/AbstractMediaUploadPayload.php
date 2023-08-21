<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

abstract class AbstractMediaUploadPayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $mediaId;

    private string $extension;

    private ?string $fileName = null;

    public function __construct(string $mediaId, string $extension, ?string $fileName)
    {
        $this->attachments = new AttachmentCollection();
        $this->mediaId = $mediaId;
        $this->extension = $extension;
        $this->fileName = $fileName;
    }

    public function getMediaId(): string
    {
        return $this->mediaId;
    }

    /**
     * @return static
     */
    public function withMediaId(string $mediaId): self
    {
        $that = clone $this;
        $that->mediaId = $mediaId;

        return $that;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return static
     */
    public function withExtension(string $extension): self
    {
        $that = clone $this;
        $that->extension = $extension;

        return $that;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @return static
     */
    public function withFileName(?string $fileName): self
    {
        $that = clone $this;
        $that->fileName = $fileName;

        return $that;
    }
}
