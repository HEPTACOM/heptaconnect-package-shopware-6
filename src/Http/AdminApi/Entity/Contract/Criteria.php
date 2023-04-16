<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class Criteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private ?int $limit = null;

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function withLimit(?int $limit): self
    {
        $that = clone $this;
        $that->limit = $limit;

        return $that;
    }
}
