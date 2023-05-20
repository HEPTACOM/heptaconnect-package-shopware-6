<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit\AdminApi\ErrorHandling;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class JsonResponseErrorHandlerTest extends TestCase
{
    public function testResponsesWithValidContentDoesNotThrowException(): void
    {
        static::expectNotToPerformAssertions();

        $baseFactory = new BaseFactory();
        $jsonStreamUtility = $baseFactory->getJsonStreamUtility();
        $errorHandler = new JsonResponseErrorHandler($jsonStreamUtility, new JsonResponseValidatorCollection());
        $request = $baseFactory->getRequestFactory()->createRequest('GET', '/');
        $response = $baseFactory->getResponseFactory()->createResponse(200, 'OK')->withBody(
            $jsonStreamUtility->fromPayloadToStream(['data' => ['foo' => 'bar']])
        );
        $errorHandler->throwException($request, $response);
    }
}
