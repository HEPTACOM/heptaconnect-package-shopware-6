<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema;

interface InfoEntitySchemaActionInterface
{
    /**
     * Gets the entity schema.
     *
     * @throws \Throwable
     */
    public function getEntitySchema(InfoEntitySchemaParams $params): InfoEntitySchemaResult;
}
