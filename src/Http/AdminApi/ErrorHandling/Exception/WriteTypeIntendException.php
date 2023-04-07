<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

final class WriteTypeIntendException extends AbstractRequestException implements RequestExceptionInterface
{
    public function __construct(
        RequestInterface $request,
        string $entityDefinition,
        string $expectedType,
        string $gotType,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            $request,
            \sprintf('Expected command for "%s" to be "%s". (Got: %s)', $entityDefinition, $expectedType, $gotType),
            0,
            $previous
        );
    }
}
