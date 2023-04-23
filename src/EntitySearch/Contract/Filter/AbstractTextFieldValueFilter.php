<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter;

abstract class AbstractTextFieldValueFilter extends AbstractFieldFilter
{
    private string $value;

    public function __construct(string $field, string $value)
    {
        parent::__construct($field);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
