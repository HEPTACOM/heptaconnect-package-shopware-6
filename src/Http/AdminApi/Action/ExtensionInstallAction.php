<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionInstall\ExtensionInstallActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionInstall\ExtensionInstallPayload;

final class ExtensionInstallAction extends AbstractActionClient implements ExtensionInstallActionInterface
{
    public function installExtension(ExtensionInstallPayload $payload): void
    {
        $path = sprintf('_action/extension/install/%s/%s', $payload->getExtensionType(), $payload->getExtensionName());
        $request = $this->generateRequest('POST', $path);
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);

        $this->parseResponse($request, $response);
    }
}
