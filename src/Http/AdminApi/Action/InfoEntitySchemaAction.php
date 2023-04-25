<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema\EntitySchema;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema\EntitySchemaCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema\InfoEntitySchemaActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema\InfoEntitySchemaParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema\InfoEntitySchemaResult;

final class InfoEntitySchemaAction extends AbstractActionClient implements InfoEntitySchemaActionInterface
{
    public function getEntitySchema(InfoEntitySchemaParams $params): InfoEntitySchemaResult
    {
        $path = '_info/entity-schema.json';
        $request = $this->generateRequest('GET', $path);
        $request = $this->addExpectedPackages($request, $params);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);

        return new InfoEntitySchemaResult(new EntitySchemaCollection(\array_map(
            static fn (array $schema): EntitySchema => new EntitySchema($schema),
            \array_values($result)
        )));
    }
}
