<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

final class OrderDocumentCreatePayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $documentTypeName;

    private DocumentPayloadCollection $documents;

    public function __construct(string $documentTypeName, ?DocumentPayloadCollection $documents = null)
    {
        $this->attachments = new AttachmentCollection();
        $this->documentTypeName = $documentTypeName;
        $this->documents = $documents ?? new DocumentPayloadCollection();
    }

    public function getDocumentTypeName(): string
    {
        return $this->documentTypeName;
    }

    public function withDocumentTypeName(string $documentTypeName): self
    {
        $that = clone $this;
        $that->documentTypeName = $documentTypeName;

        return $that;
    }

    public function getDocuments(): DocumentPayloadCollection
    {
        return $this->documents;
    }

    public function withDocuments(DocumentPayloadCollection $documents): self
    {
        $that = clone $this;
        $that->documents = $documents;

        return $that;
    }

    public function withAddedDocument(DocumentPayload $document): self
    {
        $documents = new DocumentPayloadCollection($this->documents);
        $documents->push([$document]);

        return $this->withDocuments($documents);
    }
}
