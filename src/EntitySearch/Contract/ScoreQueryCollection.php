<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<ScoreQuery>
 */
final class ScoreQueryCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return ScoreQuery::class;
    }
}
