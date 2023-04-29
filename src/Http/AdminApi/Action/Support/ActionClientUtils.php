<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
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

    public function addExpectedPackages(
        RequestInterface $request,
        ExpectedPackagesAwareInterface $expectedPackagesAware
    ): RequestInterface {
        $expectedPackages = [];

        foreach ($expectedPackagesAware->getExpectedPackageVersionConstraints() as $package => $constraints) {
            foreach ($constraints as $constraint) {
                $expectedPackages[] = \sprintf('%s: %s', $package, $constraint);
            }
        }

        if ($expectedPackages !== []) {
            $expectedPackages = \array_unique($expectedPackages);

            $request = $request->withHeader('sw-expect-packages', \implode(',', $expectedPackages));
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
