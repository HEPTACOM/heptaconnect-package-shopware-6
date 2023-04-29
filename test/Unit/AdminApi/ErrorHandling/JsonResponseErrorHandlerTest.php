<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\AdminApi\ErrorHandling;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class JsonResponseErrorHandlerTest extends TestCase
{
    public function testResponsesWithValidContentDoesNotThrowException(): void
    {
        static::expectNotToPerformAssertions();

        $jsonStreamUtility = BaseFactory::createJsonStreamUtility();
        $errorHandler = new JsonResponseErrorHandler($jsonStreamUtility, []);
        $request = BaseFactory::createRequestFactory()->createRequest('GET', '/');
        $response = BaseFactory::createResponseFactory()->createResponse(200, 'OK')->withBody(
            $jsonStreamUtility->fromPayloadToStream(['data' => ['foo' => 'bar']])
        );
        $errorHandler->throwException($request, $response);
    }
}
