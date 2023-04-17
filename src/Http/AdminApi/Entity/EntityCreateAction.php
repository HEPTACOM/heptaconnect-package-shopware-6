<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException;

final class EntityCreateAction extends AbstractActionClient implements EntityCreateActionInterface
{
    public function create(EntityCreatePayload $payload): EntityCreateResult
    {
        $request = $this->generateRequest('POST', $payload->getEntityName(), [], $payload->getPayload());
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->getClient()->sendRequest($request);
        // to trigger exceptions
        $this->parseResponse($request, $response);

        $location = $response->getHeaderLine('Location');
        $locationPattern = '#/api/(' . \preg_quote($payload->getEntityName(), '#') . ')/(.*)$#';

        if (\preg_match($locationPattern, $location, $matches) !== 1) {
            throw new EntityReferenceLocationFormatInvalidException($request, $response, $location);
        }

        return new EntityCreateResult($matches[1], $matches[2]);
    }
}
