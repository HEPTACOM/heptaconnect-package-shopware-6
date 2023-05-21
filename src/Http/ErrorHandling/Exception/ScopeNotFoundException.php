<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ScopeNotFoundException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $scope;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $scope,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Configuration for scope "%s" not found.', $scope);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->scope = $scope;
    }

    public function getScope(): string
    {
        return $this->scope;
    }
}
