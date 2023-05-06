<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

final class PriceCalculatePayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    public const OUTPUT_GROSS = 'gross';

    public const OUTPUT_NET = 'net';

    private string $taxId;

    private float $price;

    private ?int $quantity = null;

    private ?string $output = null;

    private ?bool $calculated = null;

    public function __construct(string $taxId, float $price)
    {
        $this->attachments = new AttachmentCollection();
        $this->taxId = $taxId;
        $this->price = $price;
    }

    public function getTaxId(): string
    {
        return $this->taxId;
    }

    public function withTaxId(string $taxId): self
    {
        $that = clone $this;
        $that->taxId = $taxId;

        return $that;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function withPrice(float $price): self
    {
        $that = clone $this;
        $that->price = $price;

        return $that;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function withQuantity(?int $quantity): self
    {
        $that = clone $this;
        $that->quantity = $quantity;

        return $that;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function withOutput(?string $output): self
    {
        $that = clone $this;
        $that->output = $output;

        return $that;
    }

    public function getCalculated(): ?bool
    {
        return $this->calculated;
    }

    public function withCalculated(?bool $calculated): self
    {
        $that = clone $this;
        $that->calculated = $calculated;

        return $that;
    }
}
