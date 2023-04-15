<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityDelete;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ResourceNotFoundException;

interface EntityDeleteActionInterface
{
    /**
     * Deletes the requested entity.
     *
     * @throws EntityReferenceLocationFormatInvalidException when the response does not contain a reference to the new entity
     * @throws ResourceNotFoundException                     when the entity does not exist
     * @throws \Throwable
     */
    public function delete(EntityDeleteCriteria $criteria): EntityDeleteResult;
}
