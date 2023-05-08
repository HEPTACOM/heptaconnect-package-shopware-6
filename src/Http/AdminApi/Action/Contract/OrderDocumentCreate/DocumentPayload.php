<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class DocumentPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $orderId;

    private ?bool $static = null;

    private ?string $fileType = null;

    private ?string $referencedDocumentId = null;

    private ?array $configuration = null;

    public function __construct(string $orderId)
    {
        $this->attachments = new AttachmentCollection();
        $this->orderId = $orderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function withOrderId(string $orderId): self
    {
        $that = clone $this;
        $that->orderId = $orderId;

        return $that;
    }

    public function getStatic(): ?bool
    {
        return $this->static;
    }

    public function withStatic(?bool $static): self
    {
        $that = clone $this;
        $that->static = $static;

        return $that;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function withFileType(?string $fileType): self
    {
        $that = clone $this;
        $that->fileType = $fileType;

        return $that;
    }

    public function getReferencedDocumentId(): ?string
    {
        return $this->referencedDocumentId;
    }

    public function withReferencedDocumentId(?string $referencedDocumentId): self
    {
        $that = clone $this;
        $that->referencedDocumentId = $referencedDocumentId;

        return $that;
    }

    public function getConfiguration(): ?array
    {
        return $this->configuration;
    }

    public function withConfiguration(?array $configuration): self
    {
        $that = clone $this;
        $that->configuration = $configuration;

        return $that;
    }
}
