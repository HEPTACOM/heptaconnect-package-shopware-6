<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityDelete\EntityDeleteActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityDelete\EntityDeleteCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityDelete\EntityDeleteResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;

final class EntityDeleteAction extends AbstractActionClient implements EntityDeleteActionInterface
{
    public function delete(EntityDeleteCriteria $criteria): EntityDeleteResult
    {
        $path = $this->getEntityPath($criteria->getEntityName(), $criteria->getId());
        $request = $this->generateRequest('DELETE', $path);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->getClient()->sendRequest($request);
        // to trigger exceptions
        $this->parseResponse($request, $response);

        $location = $response->getHeaderLine('Location');
        $locationPattern = '#/api/(' . \preg_quote($criteria->getEntityName(), '#') . ')/(.*)$#';

        if (\preg_match($locationPattern, $location, $matches) !== 1) {
            throw new EntityReferenceLocationFormatInvalidException($request, $location);
        }

        return new EntityDeleteResult($matches[1], $matches[2]);
    }

    private function getEntityPath(string $entity, string $id): string
    {
        return \sprintf('%s/%s', $entity, $id);
    }
}
