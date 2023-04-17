<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Contract\CriteriaFormatterInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Contract\EntityCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Message\RequestFactoryInterface;

final class EntitySearchAction extends AbstractActionClient implements EntitySearchActionInterface
{
    private CriteriaFormatterInterface $criteriaFormatter;

    public function __construct(
        AuthenticatedHttpClientInterface $client,
        RequestFactoryInterface $requestFactory,
        ApiConfigurationStorageInterface $apiConfigurationStorage,
        JsonStreamUtility $jsonStreamUtility,
        ErrorHandlerInterface $errorHandler,
        CriteriaFormatterInterface $criteriaFormatter
    ) {
        parent::__construct(
            $client,
            $requestFactory,
            $apiConfigurationStorage,
            $jsonStreamUtility,
            $errorHandler,
        );
        $this->criteriaFormatter = $criteriaFormatter;
    }

    public function search(EntitySearchCriteria $criteria): EntitySearchResult
    {
        $body = $this->criteriaFormatter->formatCriteria($criteria->getCriteria());
        $request = $this->generateRequest('POST', 'search/' . $criteria->getEntityName(), [], $body);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        return new EntitySearchResult(EntityCollection::fromList($result['data']), $result['total']);
    }
}
