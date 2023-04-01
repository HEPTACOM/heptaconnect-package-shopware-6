<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class JsonStreamUtilityTest extends TestCase
{
    public function testFromStreamToPayload(): void
    {
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $service = new JsonStreamUtility($streamFactory);

        static::assertSame([
            'foo' => 'bar',
        ], $service->fromStreamToPayload($streamFactory->createStream('{"foo": "bar"}')));
    }

    public function testFromPayloadToStream(): void
    {
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $service = new JsonStreamUtility($streamFactory);

        static::assertSame('{"foo":"bar"}', (string) $service->fromPayloadToStream([
            'foo' => 'bar',
        ]));
    }

    public function testFromPayloadToStreamKeepsFloatsAsFloats(): void
    {
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $service = new JsonStreamUtility($streamFactory);

        static::assertSame('{"foo":0.0}', (string) $service->fromPayloadToStream([
            'foo' => 0.0,
        ]));
    }

    public function testFromStreamToPayloadFailsWithJsonExceptionOnInvalidInput(): void
    {
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $service = new JsonStreamUtility($streamFactory);

        static::expectException(\JsonException::class);

        $service->fromStreamToPayload($streamFactory->createStream('{"foo":'));
    }

    public function testFromPayloadToStreamFailsWithJsonExceptionOnInvalidInput(): void
    {
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $service = new JsonStreamUtility($streamFactory);

        static::expectException(\JsonException::class);

        $service->fromPayloadToStream([
            \fopen('php://stdin', 'r'),
        ]);
    }

    public function testFromPayloadToStreamFailsWithJsonSerializeThrowingException(): void
    {
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $service = new JsonStreamUtility($streamFactory);
        $jsonSerializable = $this->createMock(\JsonSerializable::class);
        $jsonSerializable->method('jsonSerialize')->willThrowException(new \RuntimeException());

        static::expectException(\JsonException::class);

        $service->fromPayloadToStream([$jsonSerializable]);
    }
}
