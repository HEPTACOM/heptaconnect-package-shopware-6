<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Generic\GenericActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Generic\GenericPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Generic\GenericResult;

final class GenericAction extends AbstractActionClient implements GenericActionInterface
{
    public function sendGenericRequest(GenericPayload $payload): GenericResult
    {
        $request = $this->generateRequest(
            $payload->getMethod(),
            $payload->getPath(),
            $payload->getQueryParameters(),
            $payload->getBody()
        );

        foreach ($payload->getHeaders() ?? [] as $name => $value) {
            $request = $request->withAddedHeader($name, $value);
        }

        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);
        $result = $this->parseResponse($request, $response);

        return new GenericResult($response->getStatusCode(), $result, $response->getHeaders());
    }
}
