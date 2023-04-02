<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Message\RequestInterface;

final class UnknownError extends AbstractRequestException
{
    public function __construct(RequestInterface $request, ?string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($request, $message . \PHP_EOL . \json_encode([
            'request' => [
                'uri' => (string) $request->getUri(),
                'body' => (string) $request->getBody(),
            ],
        ]), 0, $previous);
    }
}
