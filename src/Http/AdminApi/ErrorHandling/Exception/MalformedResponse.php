<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

final class MalformedResponse extends AbstractRequestException implements RequestExceptionInterface
{
    public function __construct(RequestInterface $request, int $statusCode, string $body, ?\Throwable $previous = null)
    {
        parent::__construct(
            $request,
            \sprintf('Response body is malformed. Code: "%s" Body: "%s"', $statusCode, $body),
            0,
            $previous
        );
    }
}
