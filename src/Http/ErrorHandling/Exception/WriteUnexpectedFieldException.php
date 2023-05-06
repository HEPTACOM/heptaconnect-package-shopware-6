<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class WriteUnexpectedFieldException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $field;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $field,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Expected field: "%s"', $field);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
