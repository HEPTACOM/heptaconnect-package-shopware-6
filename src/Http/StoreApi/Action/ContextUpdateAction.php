<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate\ContextUpdateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate\ContextUpdatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate\ContextUpdateResult;

final class ContextUpdateAction extends AbstractActionClient implements ContextUpdateActionInterface
{
    public function updateContext(ContextUpdatePayload $payload): ContextUpdateResult
    {
        $path = 'context';
        $body = [
            'currencyId' => $payload->getCurrencyId(),
            'languageId' => $payload->getLanguageId(),
            'billingAddressId' => $payload->getBillingAddressId(),
            'shippingAddressId' => $payload->getShippingAddressId(),
            'paymentMethodId' => $payload->getPaymentMethodId(),
            'shippingMethodId' => $payload->getShippingMethodId(),
            'countryId' => $payload->getCountryId(),
            'countryStateId' => $payload->getCountryStateId(),
        ];
        $body = \array_filter($body, 'is_string');

        $request = $this->generateRequest('PATCH', $path, [], $body);
        $request = $this->addContextToken($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);

        return new ContextUpdateResult($result['contextToken'], $result['redirectUrl'] ?? null);
    }
}
