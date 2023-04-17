<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Support\ExpectedPackagesAwareTrait;

final class SystemConfigPostPayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    /**
     * @var array<string, mixed>
     */
    private array $values;

    private ?string $salesChannel;

    /**
     * @param array<string, mixed> $keyedValues
     */
    public function __construct(array $keyedValues, ?string $salesChannel = null)
    {
        $this->attachments = new AttachmentCollection();
        $this->values = $keyedValues;
        $this->salesChannel = $salesChannel;
    }

    /**
     * @return array<string, mixed>
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

    public function getSalesChannel(): ?string
    {
        return $this->salesChannel;
    }

    public function withSalesChannel(?string $salesChannel): self
    {
        $that = clone $this;
        $that->salesChannel = $salesChannel;

        return $that;
    }
}
