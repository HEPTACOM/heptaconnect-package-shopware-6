<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ContextTokenAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericPayload;

final class GenericPayload extends AbstractGenericPayload implements ContextTokenAwareInterface
{
    use ContextTokenAwareTrait;
}
