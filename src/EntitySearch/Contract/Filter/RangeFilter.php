<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter;

final class RangeFilter extends AbstractFieldFilter
{
    public const LTE = 'lte';

    public const LT = 'lt';

    public const GTE = 'gte';

    public const GT = 'gt';

    /**
     * @var array<string, string>
     */
    private array $constraints;

    /**
     * @param array<string, string> $constraints
     */
    public function __construct(string $field, array $constraints)
    {
        parent::__construct($field);
        $this->constraints = $constraints;
    }

    /**
     * @return array<string, string>
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }
}
