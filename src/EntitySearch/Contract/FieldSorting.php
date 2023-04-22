<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

final class FieldSorting
{
    public const ASCENDING = 'ASC';

    public const DESCENDING = 'DESC';

    private string $field;

    private string $direction;

    private bool $naturalSorting;

    public function __construct(
        string $field,
        string $direction = self::ASCENDING,
        bool $naturalSorting = false
    ) {
        $this->field = $field;
        $this->direction = $direction;
        $this->naturalSorting = $naturalSorting;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function isNaturalSorting(): bool
    {
        return $this->naturalSorting;
    }
}
