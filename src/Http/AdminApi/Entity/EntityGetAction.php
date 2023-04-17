<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Contract\Entity;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetResult;

final class EntityGetAction extends AbstractActionClient implements EntityGetActionInterface
{
    public function get(EntityGetCriteria $criteria): EntityGetResult
    {
        $request = $this->generateRequest('GET', $this->getEntityPath($criteria->getEntityName(), $criteria->getId()));
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        return new EntityGetResult(Entity::fromAssociative($result['data']));
    }

    private function getEntityPath(string $entity, string $id): string
    {
        return \sprintf('%s/%s', $entity, $id);
    }
}
