<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MalformedResponse extends AbstractRequestException implements RequestExceptionInterface
{
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Response body is malformed. Code: "%d"', $response->getStatusCode());
        parent::__construct($request, $response, $message, $code, $previous);
    }
}
