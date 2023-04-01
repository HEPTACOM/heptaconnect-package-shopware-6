<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;

interface ApiConfigurationStorageInterface
{
    /**
     * Returns the API configuration to use for queries, actions and authentication requests.
     *
     * @throws \Throwable
     */
    public function getConfiguration(): ApiConfiguration;
}
