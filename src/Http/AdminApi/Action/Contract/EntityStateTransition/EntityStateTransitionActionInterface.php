<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\EntityStateTransition;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\StateMachineInvalidEntityIdException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\CartMissingOrderRelationException;

interface EntityStateTransitionActionInterface
{
    /**
     * Transition the state of the given entity and returns the next state.
     *
     * @throws CartMissingOrderRelationException    if a referenced order has an invalid data structure during transition
     * @throws StateMachineInvalidEntityIdException if the referenced entity is not found
     * @throws \Throwable
     */
    public function transitionState(EntityStateTransitionPayload $payload): EntityStateTransitionResult;
}
