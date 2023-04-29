<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\ResourceNotFoundException;

interface EntityGetActionInterface
{
    /**
     * Reads the requested entity.
     *
     * @throws \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\ResourceNotFoundException when the entity does not exist
     * @throws \Throwable
     */
    public function get(EntityGetCriteria $criteria): EntityGetResult;
}
