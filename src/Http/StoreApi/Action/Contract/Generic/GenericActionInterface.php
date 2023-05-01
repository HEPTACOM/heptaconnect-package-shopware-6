<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic;

interface GenericActionInterface
{
    /**
     * Performs a generic action.
     *
     * @throws \Throwable
     */
    public function sendGenericRequest(GenericPayload $payload): GenericResult;
}
