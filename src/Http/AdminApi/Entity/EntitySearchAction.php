<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult;

final class EntitySearchAction extends AbstractActionClient implements EntitySearchActionInterface
{
    public function search(EntitySearchCriteria $criteria): EntitySearchResult
    {
        $request = $this->generateRequest('POST', 'search/' . $criteria->getEntityName(), [], []);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        return new EntitySearchResult(EntityCollection::fromList($result['data']), $result['total']);
    }
}
