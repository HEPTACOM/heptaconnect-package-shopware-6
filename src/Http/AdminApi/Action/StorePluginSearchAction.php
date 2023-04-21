<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePlugin;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginSearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginSearchParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginSearchResult;

final class StorePluginSearchAction extends AbstractActionClient implements StorePluginSearchActionInterface
{
    public function searchPluginStore(StorePluginSearchParams $params): StorePluginSearchResult
    {
        $path = '_action/store/plugin/search';
        $request = $this->generateRequest('POST', $path);
        $request = $this->addExpectedPackages($request, $params);
        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        return new StorePluginSearchResult(
            new StorePluginCollection(\array_map(
                static fn (array $item): StorePlugin => new StorePlugin($item),
                $result['items']
            )),
            $result['total']
        );
    }
}
