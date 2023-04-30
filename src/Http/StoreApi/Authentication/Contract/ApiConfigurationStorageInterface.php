<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration;

interface ApiConfigurationStorageInterface
{
    /**
     * Returns the API configuration to use for queries and actions requests.
     *
     * @throws \Throwable
     */
    public function getConfiguration(): ApiConfiguration;
}
