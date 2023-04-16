<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdResult;

final class EntitySearchIdAction extends AbstractActionClient implements EntitySearchIdActionInterface
{
    public function searchIds(EntitySearchIdCriteria $criteria): EntitySearchIdResult
    {
        $request = $this->generateRequest('POST', 'search-ids/' . $criteria->getEntityName(), [], []);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        return new EntitySearchIdResult($result['data'], $result['total']);
    }
}
