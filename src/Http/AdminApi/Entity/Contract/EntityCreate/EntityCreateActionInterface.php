<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException;

interface EntityCreateActionInterface
{
    /**
     * Create an entity with the given data.
     *
     * @throws EntityReferenceLocationFormatInvalidException when the response does not contain a reference to the new entity
     * @throws WriteTypeIntendException                      when the entity with the primary key already exists
     * @throws \Throwable
     */
    public function create(EntityCreatePayload $payload): EntityCreateResult;
}
