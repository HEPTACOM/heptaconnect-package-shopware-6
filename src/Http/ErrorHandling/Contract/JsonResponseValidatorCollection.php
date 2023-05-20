<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<JsonResponseValidatorInterface>
 */
final class JsonResponseValidatorCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return JsonResponseValidatorInterface::class;
    }
}
