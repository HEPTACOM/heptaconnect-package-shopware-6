<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

final class SyncPayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    public const USE_QUEUE_INDEXING = 'use-queue-indexing';

    public const DISABLE_INDEXING = 'disable-indexing';

    private SyncOperationCollection $syncOperations;

    private ?bool $singleOperation = null;

    private ?string $indexingBehavior = null;

    private array $indexingSkip = [];

    public function __construct()
    {
        $this->attachments = new AttachmentCollection();
        $this->syncOperations = new SyncOperationCollection();
    }

    public function getSyncOperations(): SyncOperationCollection
    {
        return $this->syncOperations;
    }

    public function withSyncOperations(SyncOperationCollection $syncOperations): self
    {
        $that = clone $this;
        $that->syncOperations = $syncOperations;

        return $that;
    }

    public function withSyncOperation(string $entityName, string $action, array $payload, ?string $key = null): self
    {
        $ops = new SyncOperationCollection($this->getSyncOperations());
        $key ??= $entityName . \bin2hex(\random_bytes(16));
        $ops->push([
            (new SyncOperation($entityName, $action, $key))->withAddedPayload($payload),
        ]);

        return $this->withSyncOperations($ops);
    }

    public function getSingleOperation(): ?bool
    {
        return $this->singleOperation;
    }

    public function withSingleOperation(?bool $singleOperation = true): self
    {
        $that = clone $this;
        $that->singleOperation = $singleOperation;

        return $that;
    }

    public function getIndexingBehavior(): ?string
    {
        return $this->indexingBehavior;
    }

    public function withIndexingBehavior(?string $indexingBehavior): self
    {
        $that = clone $this;
        $that->indexingBehavior = $indexingBehavior;

        return $that;
    }

    public function getIndexingSkip(): array
    {
        return \array_keys($this->indexingSkip);
    }

    public function withIndexingSkip(array $indexingSkips): self
    {
        $that = $this->withoutIndexingSkip();

        foreach ($indexingSkips as $indexingSkip) {
            $that = $that->withAddedIndexingSkip($indexingSkip);
        }

        return $that;
    }

    public function withoutIndexingSkip(): self
    {
        $that = clone $this;
        $that->indexingSkip = [];

        return $that;
    }

    public function withAddedIndexingSkip(string $indexingSkip): self
    {
        $that = clone $this;
        $that->indexingSkip[$indexingSkip] = true;

        return $that;
    }
}
