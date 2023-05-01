<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericResult;

final class GenericResult extends AbstractGenericResult implements AttachmentAwareInterface
{
}
