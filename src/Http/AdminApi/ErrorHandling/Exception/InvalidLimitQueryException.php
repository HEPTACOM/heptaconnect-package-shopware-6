<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Message\RequestInterface;

final class InvalidLimitQueryException extends AbstractRequestException
{
    private string $field;

    private string $limitErrorMessage;

    public function __construct(RequestInterface $request, string $limitErrorMessage, string $field, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($request, \sprintf('The value of "%s" is invalid: %s', $field, $limitErrorMessage), $code, $previous);
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
