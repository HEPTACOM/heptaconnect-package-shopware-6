<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch;

interface StorePluginSearchActionInterface
{
    /**
     * Searches for store information of extensions.
     */
    public function searchPluginStore(StorePluginSearchParams $params): StorePluginSearchResult;
}
