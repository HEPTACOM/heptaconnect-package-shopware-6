<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ResourceNotFoundException extends AbstractRequestException implements RequestExceptionInterface
{
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $entity,
        array $primaryKey,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Entity "%s" by "%s" has not been found', $entity, \json_encode($primaryKey, \JSON_PARTIAL_OUTPUT_ON_ERROR));
        parent::__construct($request, $response, $message, $code, $previous);
    }
}
