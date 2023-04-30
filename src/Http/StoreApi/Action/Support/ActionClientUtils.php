<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\ContextTokenRequiredInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A class holding dependencies and utility methods to easily create JSON requests and parse JSON responses with meaningful exceptions.
 */
final class ActionClientUtils
{
    private AuthenticatedHttpClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private ApiConfigurationStorageInterface $apiConfigurationStorage;

    private JsonStreamUtility $jsonStreamUtility;

    private ErrorHandlerInterface $errorHandler;

    public function __construct(
        AuthenticatedHttpClientInterface $client,
        RequestFactoryInterface $requestFactory,
        ApiConfigurationStorageInterface $apiConfigurationStorage,
        JsonStreamUtility $jsonStreamUtility,
        ErrorHandlerInterface $errorHandler
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->apiConfigurationStorage = $apiConfigurationStorage;
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
        $url = $this->apiConfigurationStorage->getConfiguration()->getUrl() . '/' . $path;

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
     * @param ContextTokenAwareInterface|ContextTokenRequiredInterface $contextTokenAware
     */
    public function addContextToken(RequestInterface $request, $contextTokenAware): RequestInterface
    {
        $contextToken = null;

        if ($contextTokenAware instanceof ContextTokenAwareInterface) {
            $contextToken = $contextTokenAware->getContextToken();
        }

        if ($contextTokenAware instanceof ContextTokenRequiredInterface) {
            $contextToken = $contextTokenAware->getContextToken();
        }

        if ($contextToken !== null) {
            $request = $request->withHeader('sw-context-token', $contextToken);
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
}
