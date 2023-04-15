<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdateResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;

final class EntityUpdateAction extends AbstractActionClient implements EntityUpdateActionInterface
{
    public function update(EntityUpdatePayload $payload): EntityUpdateResult
    {
        $path = $this->getEntityPath($payload->getEntityName(), $payload->getId());
        $request = $this->generateRequest('PATCH', $path, [], $payload->getPayload());
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->getClient()->sendRequest($request);
        // to trigger exceptions
        $this->parseResponse($request, $response);

        $location = $response->getHeaderLine('Location');
        $locationPattern = '#/api/(' . \preg_quote($payload->getEntityName(), '#') . ')/(.*)$#';

        if (\preg_match($locationPattern, $location, $matches) !== 1) {
            throw new EntityReferenceLocationFormatInvalidException($request, $location);
        }

        return new EntityUpdateResult($matches[1], $matches[2]);
    }

    private function getEntityPath(string $entity, string $id): string
    {
        return \sprintf('%s/%s', $entity, $id);
    }
}
