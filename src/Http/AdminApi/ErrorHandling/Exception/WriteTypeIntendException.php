<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class WriteTypeIntendException extends AbstractRequestException implements RequestExceptionInterface
{
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $entityDefinition,
        string $expectedType,
        string $gotType,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Expected command for "%s" to be "%s". (Got: %s)', $entityDefinition, $expectedType, $gotType);
        parent::__construct($request, $response, $message, $code, $previous);
    }
}
