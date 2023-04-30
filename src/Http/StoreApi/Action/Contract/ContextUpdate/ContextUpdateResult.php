<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextUpdate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class ContextUpdateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $contextToken;

    private ?string $redirectUrl;

    public function __construct(string $contextToken, ?string $redirectUrl)
    {
        $this->attachments = new AttachmentCollection();
        $this->contextToken = $contextToken;
        $this->redirectUrl = $redirectUrl;
    }

    public function getContextToken(): string
    {
        return $this->contextToken;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }
}
