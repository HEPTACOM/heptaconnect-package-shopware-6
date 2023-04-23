<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection;

final class OrFilter extends AbstractNestedFilters
{
    public function __construct(FilterCollection $filters)
    {
        parent::__construct($filters, AbstractNestedFilters::OPERATOR_OR);
    }
}
