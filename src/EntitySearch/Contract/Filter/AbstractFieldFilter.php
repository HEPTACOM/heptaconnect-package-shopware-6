<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterContract;

abstract class AbstractFieldFilter extends FilterContract
{
    private string $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
