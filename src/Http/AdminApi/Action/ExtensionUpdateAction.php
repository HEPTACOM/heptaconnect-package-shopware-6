<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpdate\ExtensionUpdateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpdate\ExtensionUpdatePayload;

final class ExtensionUpdateAction extends AbstractActionClient implements ExtensionUpdateActionInterface
{
    public function updateExtension(ExtensionUpdatePayload $payload): void
    {
        $path = sprintf('_action/extension/update/%s/%s', $payload->getExtensionType(), $payload->getExtensionName());
        $request = $this->generateRequest('POST', $path, [], [
            'allowNewPermissions' => $payload->isAllowNewPermissions(),
        ]);
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);

        $this->parseResponse($request, $response);
    }
}
