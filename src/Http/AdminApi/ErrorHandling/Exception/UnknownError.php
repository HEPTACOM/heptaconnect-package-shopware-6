<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class UnknownError extends AbstractRequestException
{
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message .= \PHP_EOL . \json_encode([
            'request' => [
                'uri' => (string) $request->getUri(),
                'body' => (string) $request->getBody(),
            ],
        ]);

        parent::__construct($request, $response, $message, $code, $previous);
    }
}
