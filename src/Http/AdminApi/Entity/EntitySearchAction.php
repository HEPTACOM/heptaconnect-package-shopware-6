<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationBucketCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResult;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\CriteriaFormatterInterface;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult;

final class EntitySearchAction extends AbstractActionClient implements EntitySearchActionInterface
{
    private CriteriaFormatterInterface $criteriaFormatter;

    public function __construct(ActionClient $actionClient, CriteriaFormatterInterface $criteriaFormatter)
    {
        parent::__construct($actionClient);
        $this->criteriaFormatter = $criteriaFormatter;
    }

    public function search(EntitySearchCriteria $criteria): EntitySearchResult
    {
        $body = $this->criteriaFormatter->formatCriteria($criteria->getCriteria());
        $request = $this->generateRequest('POST', 'search/' . $criteria->getEntityName(), [], $body);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);
        $aggregations = $result['aggregations'] ?? [];

        return new EntitySearchResult(
            EntityCollection::fromList($result['data']),
            $result['total'],
            new AggregationResultCollection(\array_map(
                static function (string $name, array $data): AggregationResult {
                    $entities = $data['entities'] ?? null;

                    if ($entities !== null) {
                        $data['entities'] = EntityCollection::fromList($entities);
                    }

                    $buckets = $data['buckets'] ?? null;

                    if ($buckets !== null) {
                        $data['buckets'] = AggregationBucketCollection::fromList($buckets);
                    }

                    return new AggregationResult($name, $data);
                },
                \array_keys($aggregations),
                $aggregations,
            ))
        );
    }
}
