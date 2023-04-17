<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class SyncOperation implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public const ACTION_UPSERT = 'upsert';

    public const ACTION_DELETE = 'delete';

    private string $entity;

    private string $action;

    private array $payload = [];

    private string $key;

    public function __construct(string $entity, string $action, string $key)
    {
        $this->attachments = new AttachmentCollection();
        $action = \strtolower($action);

        if (!\in_array($action, [self::ACTION_UPSERT, self::ACTION_DELETE], true)) {
            throw new \Exception(\sprintf('The action "%s" is not supported', $action));
        }

        $this->entity = $entity;
        $this->action = $action;
        $this->key = $key;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function withPayload(array $payloads): self
    {
        $that = $this->withoutPayload();

        foreach ($payloads as $payload) {
            $that = $that->withAddedPayload($payload);
        }

        return $that;
    }

    public function withAddedPayload(array $payload): self
    {
        $that = clone $this;
        $that->payload[] = $payload;

        return $that;
    }

    public function withoutPayload(): self
    {
        $that = clone $this;
        $that->payload = [];

        return $that;
    }
}
