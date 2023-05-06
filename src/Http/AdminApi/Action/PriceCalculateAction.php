<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate\PriceCalculateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate\PriceCalculatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate\PriceCalculateResult;

final class PriceCalculateAction extends AbstractActionClient implements PriceCalculateActionInterface
{
    public function calculatePrice(PriceCalculatePayload $payload): PriceCalculateResult
    {
        $path = '_action/calculate-price';
        $body = [
            'taxId' => $payload->getTaxId(),
            'price' => $payload->getPrice(),
        ];
        $quantity = $payload->getQuantity();
        $output = $payload->getOutput();
        $calculated = $payload->getCalculated();

        if ($quantity !== null) {
            $body['quantity'] = $quantity;
        }

        if ($output !== null) {
            $body['output'] = $output;
        }

        if ($calculated !== null) {
            $body['calculated'] = $calculated;
        }

        $request = $this->generateRequest('POST', $path, [], $body);
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);

        return new PriceCalculateResult(Entity::fromAssociative($result['data']));
    }
}
