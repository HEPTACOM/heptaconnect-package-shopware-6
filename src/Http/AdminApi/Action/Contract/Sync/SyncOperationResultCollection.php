<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<SyncOperationResult>
 */
final class SyncOperationResultCollection extends AbstractObjectCollection
{
    public function hasKey(string $key): bool
    {
        /** @var SyncOperationResult $result */
        foreach ($this as $result) {
            if ($result->getKey() === $key) {
                return true;
            }
        }

        return false;
    }

    public function getKey(string $key): ?SyncOperationResult
    {
        /* @var SyncOperationResult $syncOperation */
        foreach ($this as $result) {
            if ($result->getKey() === $key) {
                return $result;
            }
        }

        return null;
    }

    public function removeKey(string $key): self
    {
        $this->items = \array_filter(
            $this->items,
            static fn (SyncOperationResult $result): bool => $result->getKey() !== $key
        );

        return $this;
    }

    protected function getT(): string
    {
        return SyncOperationResult::class;
    }
}
