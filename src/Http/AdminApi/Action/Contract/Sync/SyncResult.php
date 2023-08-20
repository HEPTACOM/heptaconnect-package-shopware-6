<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class SyncResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var array<string, array[]>
     */
    private array $data;

    /**
     * @var array<string, array[]>
     */
    private array $deleted;

    /**
     * @var array<string, array[]>
     */
    private array $notFound;

    /**
     * @param array<string, array[]> $data
     * @param array<string, array[]> $deleted
     * @param array<string, array[]> $notFound
     */
    public function __construct(array $data, array $deleted, array $notFound)
    {
        $this->attachments = new AttachmentCollection();
        $this->data = $data;
        $this->deleted = $deleted;
        $this->notFound = $notFound;
    }

    /**
     * @return array<string, array[]>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array<string, array[]>
     */
    public function getDeleted(): array
    {
        return $this->deleted;
    }

    /**
     * @return array<string, array[]>
     */
    public function getNotFound(): array
    {
        return $this->notFound;
    }
}
