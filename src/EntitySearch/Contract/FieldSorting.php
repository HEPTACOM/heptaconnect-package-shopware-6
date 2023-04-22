<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

final class FieldSorting extends SortingContract
{
    private bool $naturalSorting;

    public function __construct(
        string $field,
        string $direction = self::ASCENDING,
        bool $naturalSorting = false
    ) {
        parent::__construct($field, $direction);
        $this->naturalSorting = $naturalSorting;
    }

    public function isNaturalSorting(): bool
    {
        return $this->naturalSorting;
    }
}
