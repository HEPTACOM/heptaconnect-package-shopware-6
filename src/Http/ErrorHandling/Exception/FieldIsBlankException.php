<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FieldIsBlankException extends AbstractRequestException
{
    private string $field;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $field,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('This value should not be blank: "%s"', $field);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
