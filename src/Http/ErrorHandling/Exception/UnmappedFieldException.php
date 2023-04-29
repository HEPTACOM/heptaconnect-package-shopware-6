<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class UnmappedFieldException extends AbstractRequestException
{
    private string $entityName;

    private string $field;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $entityName,
        string $field,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Field "%s" in entity "%s" was not found', $field, $entityName);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->entityName = $entityName;
        $this->field = $field;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
