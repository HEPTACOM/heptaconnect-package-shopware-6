<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class InvalidTypeException extends AbstractRequestException
{
    private string $field;

    private string $typeErrorMessage;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $typeErrorMessage,
        string $field,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('This type of "%s" is invalid: %s', $field, $typeErrorMessage);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->field = $field;
        $this->typeErrorMessage = $typeErrorMessage;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getTypeErrorMessage(): string
    {
        return $this->typeErrorMessage;
    }
}
