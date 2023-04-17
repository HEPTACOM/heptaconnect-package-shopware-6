<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class SyncOperationResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $key;

    private array $result;

    public function __construct(string $key, array $result)
    {
        $this->attachments = new AttachmentCollection();
        $this->key = $key;
        $this->result = $result;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function getEntities(): array
    {
        return \array_column($this->getResult(), 'entities');
    }

    public function getErrors(): array
    {
        return \array_filter(\array_column($this->getResult(), 'errors'));
    }
}
