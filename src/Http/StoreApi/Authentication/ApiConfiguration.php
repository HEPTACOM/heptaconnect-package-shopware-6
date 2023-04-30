<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class ApiConfiguration implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $url;

    private string $accessKey;

    public function __construct(string $url, string $accessKey)
    {
        $this->url = $url;
        $this->accessKey = $accessKey;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }
}
