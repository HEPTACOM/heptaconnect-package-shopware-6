<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Contract\CriteriaFormatterInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Message\RequestFactoryInterface;

final class EntitySearchIdAction extends AbstractActionClient implements EntitySearchIdActionInterface
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

    public function searchIds(EntitySearchIdCriteria $criteria): EntitySearchIdResult
    {
        $body = $this->criteriaFormatter->formatCriteria($criteria->getCriteria());
        $request = $this->generateRequest('POST', 'search-ids/' . $criteria->getEntityName(), [], $body);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        return new EntitySearchIdResult($result['data'], $result['total']);
    }
}
