<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class InvalidLimitQueryException extends AbstractRequestException
{
    private string $field;

    private string $limitErrorMessage;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $limitErrorMessage,
        string $field,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('The value of "%s" is invalid: %s', $field, $limitErrorMessage);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->field = $field;
        $this->limitErrorMessage = $limitErrorMessage;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getLimitErrorMessage(): string
    {
        return $this->limitErrorMessage;
    }
}
