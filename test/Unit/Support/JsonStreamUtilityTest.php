<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class JsonStreamUtilityTest extends TestCase
{
    public function testFromStreamToPayload(): void
    {
        $streamFactory = BaseFactory::createStreamFactory();
        $service = BaseFactory::createJsonStreamUtility();

        static::assertSame([
            'foo' => 'bar',
        ], $service->fromStreamToPayload($streamFactory->createStream('{"foo": "bar"}')));
    }

    public function testFromPayloadToStream(): void
    {
        $service = BaseFactory::createJsonStreamUtility();

        static::assertSame('{"foo":"bar"}', (string) $service->fromPayloadToStream([
            'foo' => 'bar',
        ]));
    }

    public function testFromPayloadToStreamKeepsFloatsAsFloats(): void
    {
        $service = BaseFactory::createJsonStreamUtility();

        static::assertSame('{"foo":0.0}', (string) $service->fromPayloadToStream([
            'foo' => 0.0,
        ]));
    }

    public function testFromStreamToPayloadFailsWithJsonExceptionOnInvalidInput(): void
    {
        $streamFactory = BaseFactory::createStreamFactory();
        $service = BaseFactory::createJsonStreamUtility();

        static::expectException(\JsonException::class);

        $service->fromStreamToPayload($streamFactory->createStream('{"foo":'));
    }

    public function testFromPayloadToStreamFailsWithJsonExceptionOnInvalidInput(): void
    {
        $service = BaseFactory::createJsonStreamUtility();

        static::expectException(\JsonException::class);

        $service->fromPayloadToStream([
            \fopen('php://stdin', 'rb'),
        ]);
    }

    public function testFromPayloadToStreamFailsWithJsonSerializeThrowingException(): void
    {
        $service = BaseFactory::createJsonStreamUtility();
        $jsonSerializable = $this->createMock(\JsonSerializable::class);
        $jsonSerializable->method('jsonSerialize')->willThrowException(new \RuntimeException());

        static::expectException(\JsonException::class);

        $service->fromPayloadToStream([$jsonSerializable]);
    }
}
