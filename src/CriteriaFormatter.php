<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6;

use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem;
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
        $term = $criteria->getTerm();
        $includes = $criteria->getIncludes();

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

        if ($term !== null) {
            $result['term'] = $term;
        }

        if ($includes !== null) {
            $result['includes'] = $this->getIncludeValues($includes);
        }

        return $result;
    }

    /**
     * @return array<string, list<string>>
     */
    private function getIncludeValues(TaggedStringCollection $includes): array
    {
        $result = [];

        /** @var TagItem<string> $include */
        foreach ($includes as $include) {
            $result[$include->getTag()] = $include->getCollection()->asArray();
        }

        return $result;
    }
}
