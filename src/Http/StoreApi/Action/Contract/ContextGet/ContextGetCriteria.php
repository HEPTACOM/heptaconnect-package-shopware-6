<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ContextTokenAwareTrait;

final class ContextGetCriteria implements AttachmentAwareInterface, ContextTokenAwareInterface
{
    use AttachmentAwareTrait;
    use ContextTokenAwareTrait;

    /**
     * @param string|null $contextToken Use null to get a default context. Its token is not reliable
     */
    public function __construct(?string $contextToken)
    {
        $this->attachments = new AttachmentCollection();
        $this->contextToken = $contextToken;
    }
}
