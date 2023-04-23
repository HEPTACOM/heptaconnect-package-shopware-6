<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter;

final class EqualsFilter extends AbstractFieldFilter
{
    private $value;

    public function __construct(string $field, $value)
    {
        parent::__construct($field);
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
