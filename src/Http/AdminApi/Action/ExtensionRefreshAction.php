<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRefresh\ExtensionRefreshActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRefresh\ExtensionRefreshParams;

final class ExtensionRefreshAction extends AbstractActionClient implements ExtensionRefreshActionInterface
{
    public function refreshExtensions(ExtensionRefreshParams $params): void
    {
        $path = '_action/extension/refresh';
        $request = $this->generateRequest('POST', $path);
        $request = $this->addExpectedPackages($request, $params);
        $response = $this->getClient()->sendRequest($request);

        $this->parseResponse($request, $response);
    }
}
