<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\CriteriaFormatterInterface;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult;

final class EntitySearchAction extends AbstractActionClient implements EntitySearchActionInterface
{
    private CriteriaFormatterInterface $criteriaFormatter;

    public function __construct(ActionClientUtils $actionClientUtils, CriteriaFormatterInterface $criteriaFormatter)
    {
        parent::__construct($actionClientUtils);
        $this->criteriaFormatter = $criteriaFormatter;
    }

    public function search(EntitySearchCriteria $criteria): EntitySearchResult
    {
        $body = $this->criteriaFormatter->formatCriteria($criteria->getCriteria());
        $request = $this->generateRequest('POST', 'search/' . $criteria->getEntityName(), [], $body);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);

        return new EntitySearchResult(
            EntityCollection::fromList($result['data']),
            $result['total'],
            AggregationResultCollection::fromList($result['aggregations'] ?? [])
        );
    }
}
