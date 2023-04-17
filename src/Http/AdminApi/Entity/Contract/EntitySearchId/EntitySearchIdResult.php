<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class EntitySearchIdResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var list<string>|list<array<string, string>>
     */
    private array $data;

    private ?int $total;

    /**
     * @param list<string>|list<array<string, string>> $data
     */
    public function __construct(array $data, ?int $total)
    {
        $this->attachments = new AttachmentCollection();
        $this->data = $data;
        $this->total = $total;
    }

    /**
     * @return list<string>|list<array<string, string>>
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }
}
