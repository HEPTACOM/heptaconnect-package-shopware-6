<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6;

use Heptacom\HeptaConnect\Package\Shopware6\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\Contract\CriteriaFormatterInterface;

final class CriteriaFormatter implements CriteriaFormatterInterface
{
    public function formatCriteria(Criteria $criteria): array
    {
        $result = [];
        $limit = $criteria->getLimit();
        $totalCountMode = $criteria->getTotalCountMode();
        $page = $criteria->getPage();
        $ids = $criteria->getIds();

        if ($limit !== null) {
            $result['limit'] = $limit;
        }

        if ($totalCountMode !== null) {
            $result['total-count-mode'] = $totalCountMode;
        }

        if ($page !== null) {
            $result['page'] = $page;
        }

        if ($ids !== null) {
            $result['ids'] = $ids;
        }

        return $result;
    }
}
