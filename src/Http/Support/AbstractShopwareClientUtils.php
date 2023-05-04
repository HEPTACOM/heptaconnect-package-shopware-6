<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A class holding dependencies and utility methods to easily create JSON requests and parse JSON responses with meaningful exceptions.
 */
abstract class AbstractShopwareClientUtils
{
    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private JsonStreamUtility $jsonStreamUtility;

    private ErrorHandlerInterface $errorHandler;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        JsonStreamUtility $jsonStreamUtility,
        ErrorHandlerInterface $errorHandler
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->jsonStreamUtility = $jsonStreamUtility;
        $this->errorHandler = $errorHandler;
    }

    public function sendAuthenticatedRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }

    public function generateRequest(
        string $method,
        string $path,
        array $params = [],
        ?array $payload = null
    ): RequestInterface {
        $url = $this->getBaseUrl() . '/' . $path;

        if ($params !== []) {
            $url .= '?' . \http_build_query($params);
        }

        $request = $this->requestFactory
            ->createRequest(\strtoupper($method), $url)
            ->withAddedHeader('Accept', 'application/json');

        if ($payload !== null) {
            $request = $request
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->jsonStreamUtility->fromPayloadToStream($payload));
        }

        return $request;
    }

    /**
     * @throws \Throwable
     */
    public function parseResponse(RequestInterface $request, ResponseInterface $response): ?array
    {
        if ($response->getStatusCode() === 204) {
            return null;
        }

        $this->errorHandler->throwException($request, $response);

        return $this->jsonStreamUtility->fromStreamToPayload($response->getBody());
    }

    abstract protected function getBaseUrl(): string;
}
