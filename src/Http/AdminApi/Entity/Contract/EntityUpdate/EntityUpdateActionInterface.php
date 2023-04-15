<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException;

interface EntityUpdateActionInterface
{
    /**
     * Updates an entity with the given data.
     *
     * @throws EntityReferenceLocationFormatInvalidException when the response does not contain a reference to the new entity
     * @throws WriteTypeIntendException                      when the entity with the primary key does not already exists
     * @throws \Throwable
     */
    public function update(EntityUpdatePayload $payload): EntityUpdateResult;
}
