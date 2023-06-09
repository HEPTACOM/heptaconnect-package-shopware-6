<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class InfoVersionResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $version;

    public function __construct(string $version)
    {
        $this->attachments = new AttachmentCollection();
        $this->version = $version;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
