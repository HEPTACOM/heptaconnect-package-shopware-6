<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class Criteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * no total count will be selected. Should be used if no pagination required (fastest)
     */
    public const TOTAL_COUNT_MODE_NONE = 0;

    /**
     * exact total count will be selected. Should be used if an exact pagination is required (slow)
     */
    public const TOTAL_COUNT_MODE_EXACT = 1;

    /**
     * fetches limit * 5 + 1. Should be used if pagination can work with "next page exists" (fast)
     */
    public const TOTAL_COUNT_MODE_NEXT_PAGES = 2;

    private ?int $limit = null;

    private ?int $totalCountMode = null;

    private ?int $page = null;

    /**
     * @var list<string>|list<list<string>>|null
     */
    private ?array $ids = null;

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

    public function getTotalCountMode(): ?int
    {
        return $this->totalCountMode;
    }

    public function withTotalCountMode(?int $totalCountMode): self
    {
        $that = clone $this;
        $that->totalCountMode = $totalCountMode;

        return $that;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function withPage(?int $page): self
    {
        $that = clone $this;
        $that->page = $page;

        return $that;
    }

    /**
     * @return list<string>|list<list<string>>|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param list<string>|list<list<string>>|null $ids
     */
    public function withIds(?array $ids): self
    {
        $that = clone $this;
        $that->ids = $ids;

        return $that;
    }
}
