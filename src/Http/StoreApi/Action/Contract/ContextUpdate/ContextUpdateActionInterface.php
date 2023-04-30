<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\Exception\CustomerNotLoggedInException;

interface ContextUpdateActionInterface
{
    /**
     * Updates properties on the given context.
     *
     * @throws CustomerNotLoggedInException if a context property is changed, that needs a logged in customer
     * @throws \Throwable
     */
    public function updateContext(ContextUpdatePayload $payload): ContextUpdateResult;
}
