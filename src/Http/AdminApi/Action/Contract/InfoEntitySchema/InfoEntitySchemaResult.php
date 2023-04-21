<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoEntitySchema;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class InfoEntitySchemaResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private EntitySchemaCollection $schemas;

    public function __construct(EntitySchemaCollection $schemas)
    {
        $this->attachments = new AttachmentCollection();
        $this->schemas = $schemas;
    }

    public function getSchemas(): EntitySchemaCollection
    {
        return $this->schemas;
    }
}
