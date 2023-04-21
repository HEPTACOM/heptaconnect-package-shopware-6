<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<StorePlugin>
 */
final class StorePluginCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return StorePlugin::class;
    }
}
