<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Generic;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait;

final class GenericPayload implements AttachmentAwareInterface, ExpectedPackagesAwareInterface
{
    use AttachmentAwareTrait;
    use ExpectedPackagesAwareTrait;

    private string $path;

    private string $method;

    private ?array $queryParameters = null;

    private ?array $body = null;

    /**
     * @var array<string, string|string[]>|null
     */
    private ?array $headers = null;

    public function __construct(string $path, string $method)
    {
        $this->attachments = new AttachmentCollection();
        $this->path = $path;
        $this->method = $method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function withPath(string $path): self
    {
        $that = clone $this;
        $that->path = $path;

        return $that;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): self
    {
        $that = clone $this;
        $that->method = $method;

        return $that;
    }

    public function getQueryParameters(): ?array
    {
        return $this->queryParameters;
    }

    public function withQueryParameters(?array $queryParameters): self
    {
        $that = clone $this;
        $that->queryParameters = $queryParameters;

        return $that;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    public function withBody(?array $body): self
    {
        $that = clone $this;
        $that->body = $body;

        return $that;
    }

    /**
     * @return array<string, string|string[]>|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * @param array<string, string|string[]>|null $headers
     */
    public function withHeaders(?array $headers): self
    {
        $that = clone $this;
        $that->headers = $headers;

        return $that;
    }
}
