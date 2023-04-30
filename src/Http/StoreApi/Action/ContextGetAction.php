<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet\ContextGetActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet\ContextGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet\ContextGetResult;

final class ContextGetAction extends AbstractActionClient implements ContextGetActionInterface
{
    public function getContext(ContextGetCriteria $criteria): ContextGetResult
    {
        $path = 'context';
        $request = $this->generateRequest('GET', $path);
        $request = $this->addContextToken($request, $criteria);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);

        return new ContextGetResult(Entity::fromAssociative($result));
    }
}
