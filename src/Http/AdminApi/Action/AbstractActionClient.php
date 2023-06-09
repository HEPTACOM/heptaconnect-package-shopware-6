<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A base class with utility methods to easily create JSON requests and parse JSON responses with meaningful exceptions.
 */
abstract class AbstractActionClient
{
    protected ActionClientUtils $actionClientUtils;

    public function __construct(ActionClientUtils $actionClientUtils)
    {
        $this->actionClientUtils = $actionClientUtils;
    }

    protected function generateRequest(
        string $method,
        string $path,
        array $params = [],
        ?array $payload = null
    ): RequestInterface {
        return $this->actionClientUtils->generateRequest($method, $path, $params, $payload);
    }

    protected function addExpectedPackages(
        RequestInterface $request,
        ExpectedPackagesAwareInterface $expectedPackagesAware
    ): RequestInterface {
        return $this->actionClientUtils->addExpectedPackages($request, $expectedPackagesAware);
    }

    /**
     * @throws \Throwable
     */
    protected function parseResponse(RequestInterface $request, ResponseInterface $response): ?array
    {
        return $this->actionClientUtils->parseResponse($request, $response);
    }

    protected function sendAuthenticatedRequest(RequestInterface $request): ResponseInterface
    {
        return $this->actionClientUtils->sendAuthenticatedRequest($request);
    }
}
