<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch;

interface EntitySearchActionInterface
{
    /**
     * Searches for entities of the given entity name.
     *
     * @throws \Throwable
     */
    public function search(EntitySearchCriteria $criteria): EntitySearchResult;
}
