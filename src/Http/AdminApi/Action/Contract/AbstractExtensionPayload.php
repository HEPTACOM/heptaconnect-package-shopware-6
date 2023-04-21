<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

abstract class AbstractExtensionPayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $extensionType;

    private string $extensionName;

    public function __construct(string $extensionType, string $extensionName)
    {
        $this->attachments = new AttachmentCollection();
        $this->extensionType = $extensionType;
        $this->extensionName = $extensionName;
    }

    public function getExtensionType(): string
    {
        return $this->extensionType;
    }

    public function withExtensionType(string $extensionType): self
    {
        $that = clone $this;
        $that->extensionType = $extensionType;

        return $that;
    }

    public function getExtensionName(): string
    {
        return $this->extensionName;
    }

    /**
     * @return static
     */
    public function withExtensionName(string $extensionName): self
    {
        $that = clone $this;
        $that->extensionName = $extensionName;

        return $that;
    }
}
