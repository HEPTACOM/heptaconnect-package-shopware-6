<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRemove\ExtensionRemoveActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRemove\ExtensionRemovePayload;

final class ExtensionRemoveAction extends AbstractActionClient implements ExtensionRemoveActionInterface
{
    public function removeExtension(ExtensionRemovePayload $payload): void
    {
        $path = sprintf('_action/extension/remove/%s/%s', $payload->getExtensionType(), $payload->getExtensionName());
        $request = $this->generateRequest('DELETE', $path);
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);

        $this->parseResponse($request, $response);
    }
}
