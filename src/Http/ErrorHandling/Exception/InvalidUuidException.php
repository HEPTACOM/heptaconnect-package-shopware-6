<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class InvalidUuidException extends AbstractRequestException
{
    private string $input;

    private string $errorMessage;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $errorMessage,
        string $input,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('The value "%s" is not a valid UUID: %s', $input, $errorMessage);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->input = $input;
        $this->errorMessage = $errorMessage;
    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
