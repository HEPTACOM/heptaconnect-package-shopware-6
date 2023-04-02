<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

final class SyncOperationCollection extends AbstractObjectCollection
{
    public function hasKey(string $key): bool
    {
        /** @var SyncOperation $syncOperation */
        foreach ($this as $syncOperation) {
            if ($syncOperation->getKey() === $key) {
                return true;
            }
        }

        return false;
    }

    public function getKey(string $key): ?SyncOperation
    {
        /** @var SyncOperation $syncOperation */
        foreach ($this as $syncOperation) {
            if ($syncOperation->getKey() === $key) {
                return $syncOperation;
            }
        }

        return null;
    }

    public function removeKey(string $key): self
    {
        $this->items = \array_filter(
            $this->items,
            static fn (SyncOperation $operation): bool => $operation->getKey() !== $key
        );

        return $this;
    }

    protected function getT(): string
    {
        return SyncOperation::class;
    }
}
