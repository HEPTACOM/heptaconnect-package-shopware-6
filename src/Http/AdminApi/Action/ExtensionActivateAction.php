<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionActivate\ExtensionActivateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionActivate\ExtensionActivatePayload;

final class ExtensionActivateAction extends AbstractActionClient implements ExtensionActivateActionInterface
{
    public function activateExtension(ExtensionActivatePayload $payload): void
    {
        $path = sprintf('_action/extension/activate/%s/%s', $payload->getExtensionType(), $payload->getExtensionName());
        $request = $this->generateRequest('PUT', $path);
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->getClient()->sendRequest($request);
        $this->parseResponse($request, $response);
    }
}
