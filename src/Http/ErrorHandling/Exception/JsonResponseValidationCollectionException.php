<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class JsonResponseValidationCollectionException extends AbstractRequestException
{
    /**
     * @var list<\Throwable>
     */
    private array $exceptions;

    /**
     * @param list<\Throwable> $exceptions
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        array $exceptions,
        string $message,
        int $code = 0
    ) {
        parent::__construct($request, $response, $message, $code);
        $this->exceptions = $exceptions;
    }

    /**
     * @return list<\Throwable>
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
