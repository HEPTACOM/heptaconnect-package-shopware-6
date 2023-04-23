<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

final class ScoreQuery
{
    private FilterContract $filter;

    private ?float $score;

    private ?string $scoreField;

    public function __construct(
        FilterContract $filter,
        ?float $score = null,
        ?string $scoreField = null
    ) {
        $this->filter = $filter;
        $this->score = $score;
        $this->scoreField = $scoreField;
    }

    public function getFilter(): FilterContract
    {
        return $this->filter;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function getScoreField(): ?string
    {
        return $this->scoreField;
    }
}
