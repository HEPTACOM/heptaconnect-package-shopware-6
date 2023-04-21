<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallPayload;

final class ExtensionUninstallAction extends AbstractActionClient implements ExtensionUninstallActionInterface
{
    public function uninstallExtension(ExtensionUninstallPayload $payload): void
    {
        $path = sprintf('_action/extension/uninstall/%s/%s', $payload->getExtensionType(), $payload->getExtensionName());
        $request = $this->generateRequest('POST', $path, [], [
            'keepUserData' => $payload->isKeepUserData(),
        ]);
        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->getClient()->sendRequest($request);

        $this->parseResponse($request, $response);
    }
}
