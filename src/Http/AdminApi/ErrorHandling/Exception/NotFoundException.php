<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

final class NotFoundException extends AbstractRequestException implements RequestExceptionInterface
{
    public function __construct(RequestInterface $request, string $message, ?\Throwable $previous = null)
    {
        parent::__construct($request, $message, 0, $previous);
    }
}
