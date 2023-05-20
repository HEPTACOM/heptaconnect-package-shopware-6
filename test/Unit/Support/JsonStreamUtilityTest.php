<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class JsonStreamUtilityTest extends TestCase
{
    public function testFromStreamToPayload(): void
    {
        $baseFactory = new BaseFactory();
        $streamFactory = $baseFactory->getStreamFactory();
        $service = $baseFactory->getJsonStreamUtility();

        static::assertSame([
            'foo' => 'bar',
        ], $service->fromStreamToPayload($streamFactory->createStream('{"foo": "bar"}')));
    }

    public function testFromPayloadToStream(): void
    {
        $baseFactory = new BaseFactory();
        $service = $baseFactory->getJsonStreamUtility();

        static::assertSame('{"foo":"bar"}', (string) $service->fromPayloadToStream([
            'foo' => 'bar',
        ]));
    }

    public function testFromPayloadToStreamKeepsFloatsAsFloats(): void
    {
        $baseFactory = new BaseFactory();
        $service = $baseFactory->getJsonStreamUtility();

        static::assertSame('{"foo":0.0}', (string) $service->fromPayloadToStream([
            'foo' => 0.0,
        ]));
    }

    public function testFromStreamToPayloadFailsWithJsonExceptionOnInvalidInput(): void
    {
        $baseFactory = new BaseFactory();
        $streamFactory = $baseFactory->getStreamFactory();
        $service = $baseFactory->getJsonStreamUtility();

        static::expectException(\JsonException::class);

        $service->fromStreamToPayload($streamFactory->createStream('{"foo":'));
    }

    public function testFromPayloadToStreamFailsWithJsonExceptionOnInvalidInput(): void
    {
        $baseFactory = new BaseFactory();
        $service = $baseFactory->getJsonStreamUtility();

        static::expectException(\JsonException::class);

        $service->fromPayloadToStream([
            \fopen('php://stdin', 'rb'),
        ]);
    }

    public function testFromPayloadToStreamFailsWithJsonSerializeThrowingException(): void
    {
        $baseFactory = new BaseFactory();
        $service = $baseFactory->getJsonStreamUtility();
        $jsonSerializable = $this->createMock(\JsonSerializable::class);
        $jsonSerializable->method('jsonSerialize')->willThrowException(new \RuntimeException());

        static::expectException(\JsonException::class);

        $service->fromPayloadToStream([$jsonSerializable]);
    }
}
