<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate;

interface PriceCalculateActionInterface
{
    /**
     * Calculates a price.
     *
     * @throws \Throwable
     */
    public function calculatePrice(PriceCalculatePayload $payload): PriceCalculateResult;
}
