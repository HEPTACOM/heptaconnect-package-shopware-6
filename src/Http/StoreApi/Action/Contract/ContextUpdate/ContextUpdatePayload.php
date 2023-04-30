<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenRequiredInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ContextTokenRequiredTrait;

final class ContextUpdatePayload implements AttachmentAwareInterface, ContextTokenRequiredInterface
{
    use AttachmentAwareTrait;
    use ContextTokenRequiredTrait;

    private ?string $currencyId = null;

    private ?string $languageId = null;

    private ?string $billingAddressId = null;

    private ?string $shippingAddressId = null;

    private ?string $paymentMethodId = null;

    private ?string $shippingMethodId = null;

    private ?string $countryId = null;

    private ?string $countryStateId = null;

    public function __construct(string $contextToken)
    {
        $this->attachments = new AttachmentCollection();
        $this->contextToken = $contextToken;
    }

    public function getCurrencyId(): ?string
    {
        return $this->currencyId;
    }

    public function withCurrencyId(?string $currencyId): self
    {
        $that = clone $this;
        $that->currencyId = $currencyId;

        return $that;
    }

    public function getLanguageId(): ?string
    {
        return $this->languageId;
    }

    public function withLanguageId(?string $languageId): self
    {
        $that = clone $this;
        $that->languageId = $languageId;

        return $that;
    }

    public function getBillingAddressId(): ?string
    {
        return $this->billingAddressId;
    }

    public function withBillingAddressId(?string $billingAddressId): self
    {
        $that = clone $this;
        $that->billingAddressId = $billingAddressId;

        return $that;
    }

    public function getShippingAddressId(): ?string
    {
        return $this->shippingAddressId;
    }

    public function withShippingAddressId(?string $shippingAddressId): self
    {
        $that = clone $this;
        $that->shippingAddressId = $shippingAddressId;

        return $that;
    }

    public function getPaymentMethodId(): ?string
    {
        return $this->paymentMethodId;
    }

    public function withPaymentMethodId(?string $paymentMethodId): self
    {
        $that = clone $this;
        $that->paymentMethodId = $paymentMethodId;

        return $that;
    }

    public function getShippingMethodId(): ?string
    {
        return $this->shippingMethodId;
    }

    public function withShippingMethodId(?string $shippingMethodId): self
    {
        $that = clone $this;
        $that->shippingMethodId = $shippingMethodId;

        return $that;
    }

    public function getCountryId(): ?string
    {
        return $this->countryId;
    }

    public function withCountryId(?string $countryId): self
    {
        $that = clone $this;
        $that->countryId = $countryId;

        return $that;
    }

    public function getCountryStateId(): ?string
    {
        return $this->countryStateId;
    }

    public function withCountryStateId(?string $countryStateId): self
    {
        $that = clone $this;
        $that->countryStateId = $countryStateId;

        return $that;
    }
}
