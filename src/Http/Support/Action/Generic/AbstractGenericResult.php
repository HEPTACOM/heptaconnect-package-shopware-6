<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

abstract class AbstractGenericResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private int $statusCode;

    private ?array $body;

    private array $headers;

    public function __construct(int $statusCode, ?array $body, array $headers)
    {
        $this->attachments = new AttachmentCollection();
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
