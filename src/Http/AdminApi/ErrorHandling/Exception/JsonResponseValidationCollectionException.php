<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Message\RequestInterface;

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
        array $exceptions,
        ?string $message = '',
        int $code = 0
    ) {
        parent::__construct($request, $message, $code);
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
