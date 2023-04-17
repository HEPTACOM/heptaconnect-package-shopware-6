<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Support\ExpectedPackagesAwareTrait;

final class SystemConfigGetCriteria implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $configurationDomain;

    private ?string $salesChannel;

    public function __construct(string $configurationDomain, ?string $salesChannel = null)
    {
        $this->attachments = new AttachmentCollection();
        $this->configurationDomain = $configurationDomain;
        $this->salesChannel = $salesChannel;
    }

    public function getConfigurationDomain(): string
    {
        return $this->configurationDomain;
    }

    public function withConfigurationDomain(string $configurationDomain): self
    {
        $that = clone $this;
        $that->configurationDomain = $configurationDomain;

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
