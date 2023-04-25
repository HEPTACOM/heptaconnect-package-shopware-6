<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\CriteriaFormatterInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdResult;

final class EntitySearchIdAction extends AbstractActionClient implements EntitySearchIdActionInterface
{
    private CriteriaFormatterInterface $criteriaFormatter;

    public function __construct(ActionClient $actionClient, CriteriaFormatterInterface $criteriaFormatter)
    {
        parent::__construct($actionClient);
        $this->criteriaFormatter = $criteriaFormatter;
    }

    public function searchIds(EntitySearchIdCriteria $criteria): EntitySearchIdResult
    {
        $body = $this->criteriaFormatter->formatCriteria($criteria->getCriteria());
        $request = $this->generateRequest('POST', 'search-ids/' . $criteria->getEntityName(), [], $body);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);

        return new EntitySearchIdResult($result['data'], $result['total']);
    }
}
