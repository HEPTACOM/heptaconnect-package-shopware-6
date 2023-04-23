<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterContract;

final class EqualsAnyFilter extends FilterContract
{
    private string $field;

    private array $values;

    public function __construct(string $field, array $values)
    {
        $this->field = $field;
        $this->values = $values;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
