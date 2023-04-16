<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId;

interface EntitySearchIdActionInterface
{
    /**
     * Searches for entity ids of the given entity name.
     *
     * @throws \Throwable
     */
    public function searchIds(EntitySearchIdCriteria $criteria): EntitySearchIdResult;
}
