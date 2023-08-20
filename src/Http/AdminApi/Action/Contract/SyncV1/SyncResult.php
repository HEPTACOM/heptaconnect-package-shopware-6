<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SyncV1;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class SyncResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private SyncOperationResultCollection $operationResults;

    public function __construct(SyncOperationResultCollection $operationResults)
    {
        $this->attachments = new AttachmentCollection();
        $this->operationResults = $operationResults;
    }

    public function getOperationResults(): SyncOperationResultCollection
    {
        return $this->operationResults;
    }
}
