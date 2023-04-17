<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\CriteriaFormatterInterface;

final class CriteriaFormatter implements CriteriaFormatterInterface
{
    public function formatCriteria(Criteria $criteria): array
    {
        $result = [];
        $limit = $criteria->getLimit();

        if ($limit !== null) {
            $result['limit'] = $limit;
        }

        return $result;
    }
}