<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Message\RequestInterface;

final class FieldIsBlankException extends AbstractRequestException
{
    private string $field;

    public function __construct(RequestInterface $request, string $field, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($request, \sprintf('This value should not be blank: "%s"', $field), $code, $previous);
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
