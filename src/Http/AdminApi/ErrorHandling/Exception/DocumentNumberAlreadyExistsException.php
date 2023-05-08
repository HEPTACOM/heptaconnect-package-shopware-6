<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class DocumentNumberAlreadyExistsException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $number;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $number,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Document number "%s" has already been allocated', $number);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->number = $number;
    }

    public function getNumber(): string
    {
        return $this->number;
    }
}
