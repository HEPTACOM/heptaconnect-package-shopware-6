<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionDeactivate\ExtensionDeactivateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionDeactivate\ExtensionDeactivatePayload;

final class ExtensionDeactivateAction extends AbstractActionClient implements ExtensionDeactivateActionInterface
{
    public function deactivateExtension(ExtensionDeactivatePayload $payload): void
    {
        $path = sprintf('_action/extension/deactivate/%s/%s', $payload->getExtensionType(), $payload->getExtensionName());
        $request = $this->generateRequest('PUT', $path);
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);

        $this->parseResponse($request, $response);
    }
}
