<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Support;

use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class JsonStreamUtility
{
    private StreamFactoryInterface $streamFactory;

    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    /**
     * @throws \JsonException
     */
    public function fromPayloadToStream(array $payload): StreamInterface
    {
        try {
            $content = \json_encode($payload, \JSON_UNESCAPED_SLASHES | \JSON_PRESERVE_ZERO_FRACTION | \JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            if (!$exception instanceof \JsonException) {
                throw new \JsonException('JSON data preparation failed', 1680371700, $exception);
            }

            throw $exception;
        }

        return $this->streamFactory->createStream($content);
    }

    /**
     * @throws \JsonException
     */
    public function fromStreamToPayload(StreamInterface $stream): array
    {
        $result = \json_decode((string) $stream, true, 512, \JSON_THROW_ON_ERROR);

        if (!\is_array($result)) {
            throw new \JsonException('JSON result is expected to be an array', 1680371701);
        }

        return $result;
    }
}
