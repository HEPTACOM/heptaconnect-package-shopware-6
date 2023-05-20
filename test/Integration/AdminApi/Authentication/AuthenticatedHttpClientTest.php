<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Authentication;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class AuthenticatedHttpClientTest extends TestCase
{
    public function testAddAuthorizationHeader(): void
    {
        $baseFactory = new BaseFactory();

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->method('sendRequest')->willReturnCallback(function (RequestInterface $request) use ($baseFactory): ResponseInterface {
            static::assertSame('foobar', $request->getHeaderLine('Authorization'));

            return $baseFactory->getResponseFactory()->createResponse();
        });
        $authenticationStorage = $this->createMock(AuthenticationInterface::class);
        $authenticationStorage->expects(static::atLeastOnce())->method('getAuthorizationHeader')->willReturn('foobar');

        $service = new AuthenticatedHttpClient($httpClient, $authenticationStorage);
        $request = $baseFactory->getRequestFactory()->createRequest('GET', '/');
        $response = $service->sendRequest($request);

        static::assertSame(200, $response->getStatusCode());
    }

    public function testRetryWithInvalidAuthentication(): void
    {
        $baseFactory = new BaseFactory();
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->method('sendRequest')->willReturnCallback(function (RequestInterface $request) use ($baseFactory): ResponseInterface {
            $auth = $request->getHeaderLine('Authorization');
            static::assertContains($auth, ['valid', 'invalid']);

            return $baseFactory->getResponseFactory()
                ->createResponse($auth === 'invalid' ? 401 : 200);
        });
        $authenticationStorage = $this->createMock(AuthenticationInterface::class);
        $authenticationStorage->expects(static::atLeastOnce())->method('refresh');
        $authenticationStorage->expects(static::atLeastOnce())->method('getAuthorizationHeader')->willReturn('invalid', 'valid');

        $service = new AuthenticatedHttpClient($httpClient, $authenticationStorage);
        $request = $baseFactory->getRequestFactory()->createRequest('GET', '/');
        $response = $service->sendRequest($request);

        static::assertSame(200, $response->getStatusCode());
    }
}
