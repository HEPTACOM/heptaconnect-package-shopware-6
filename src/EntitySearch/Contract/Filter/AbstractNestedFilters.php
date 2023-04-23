<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterContract;

abstract class AbstractNestedFilters extends FilterContract
{
    public const OPERATOR_AND = 'AND';

    public const OPERATOR_OR = 'OR';

    public const OPERATOR_XOR = 'XOR';

    private FilterCollection $filters;

    private string $operator;

    public function __construct(FilterCollection $filters, string $operator)
    {
        $this->filters = $filters;
        $this->operator = $operator;
    }

    public function getFilters(): FilterCollection
    {
        return $this->filters;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }
}
