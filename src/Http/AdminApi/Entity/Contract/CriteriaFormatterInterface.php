<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract;

interface CriteriaFormatterInterface
{
    /**
     * Convert a criteria into a scalar-only array, that can be used in web request.
     *
     * @return array<string, array|scalar>
     */
    public function formatCriteria(Criteria $criteria): array;
}
