<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Message\RequestInterface;

final class InvalidTypeException extends AbstractRequestException
{
    private string $field;

    private string $typeErrorMessage;

    public function __construct(RequestInterface $request, string $typeErrorMessage, string $field, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($request, \sprintf('This type of "%s" is invalid: %s', $field, $typeErrorMessage), $code, $previous);
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
