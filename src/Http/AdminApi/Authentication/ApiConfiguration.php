<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class ApiConfiguration implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $grantType;

    private string $url;

    private string $username;

    private string $secret;

    private array $scopes;

    public function __construct(
        string $grantType,
        string $url,
        string $username,
        string $secret,
        array $scopes
    ) {
        $this->grantType = $grantType;
        $this->url = $url;
        $this->username = $username;
        $this->secret = $secret;
        $this->scopes = $scopes;
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }
}
