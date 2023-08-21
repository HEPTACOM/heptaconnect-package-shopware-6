<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

final class OrderDocumentGetCriteria implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $documentId;

    private string $deepLinkCode;

    public function __construct(string $documentId, string $deepLinkCode)
    {
        $this->attachments = new AttachmentCollection();
        $this->documentId = $documentId;
        $this->deepLinkCode = $deepLinkCode;
    }

    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    public function withDocumentId(string $documentId): self
    {
        $that = clone $this;
        $that->documentId = $documentId;

        return $that;
    }

    public function getDeepLinkCode(): string
    {
        return $this->deepLinkCode;
    }

    public function withDeepLinkCode(string $deepLinkCode): self
    {
        $that = clone $this;
        $that->deepLinkCode = $deepLinkCode;

        return $that;
    }
}
