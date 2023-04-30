<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet;

interface ContextGetActionInterface
{
    /**
     * Gets a context used in actions.
     *
     * @throws \Throwable
     */
    public function getContext(ContextGetCriteria $criteria): ContextGetResult;
}
