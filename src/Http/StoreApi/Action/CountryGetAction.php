<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\CriteriaFormatterInterface;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet\CountryGetActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet\CountryGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet\CountryGetResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ActionClientUtils;

final class CountryGetAction extends AbstractActionClient implements CountryGetActionInterface
{
    private CriteriaFormatterInterface $criteriaFormatter;

    public function __construct(
        ActionClientUtils $actionClientUtils,
        CriteriaFormatterInterface $criteriaFormatter
    ) {
        parent::__construct($actionClientUtils);
        $this->criteriaFormatter = $criteriaFormatter;
    }

    public function getCountries(CountryGetCriteria $criteria): CountryGetResult
    {
        $body = $this->criteriaFormatter->formatCriteria($criteria->getCriteria());
        $request = $this->generateRequest('POST', 'country', [], $body);
        $request = $this->addContextToken($request, $criteria);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);
        $aggregations = $result['aggregations'] ?? [];

        return new CountryGetResult(
            EntityCollection::fromList($result['elements']),
            AggregationResultCollection::fromList($aggregations),
            $result['total'],
            $result['page'],
            $result['limit'],
            new StringCollection($result['states'])
        );
    }
}
