<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

final class SystemConfigBatchPayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    public const GLOBAL_SALES_CHANNEL = 'null';

    /**
     * @var array<string, array<string, mixed>>
     */
    private array $values;

    /**
     * @param array<string, array<string, mixed>> $salesChannelKeyedValues use key 'null' to apply global configuration
     */
    public function __construct(array $salesChannelKeyedValues)
    {
        $this->attachments = new AttachmentCollection();
        $this->values = $salesChannelKeyedValues;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function withValues(array $salesChannelKeyedValues): self
    {
        $that = clone $this;
        $that->values = $salesChannelKeyedValues;

        return $that;
    }
}
