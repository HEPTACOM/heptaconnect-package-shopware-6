<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\EntityStateTransition\EntityStateTransitionActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\EntityStateTransition\EntityStateTransitionPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\EntityStateTransition\EntityStateTransitionResult;

final class EntityStateTransitionAction extends AbstractActionClient implements EntityStateTransitionActionInterface
{
    public function transitionState(EntityStateTransitionPayload $payload): EntityStateTransitionResult
    {
        $path = \sprintf('_action/%s/%s/state/%s', $payload->getEntityName(), $payload->getId(), $payload->getTransition());
        $request = $this->generateRequest('POST', $path);
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);

        return new EntityStateTransitionResult(Entity::fromAssociative($result));
    }
}
