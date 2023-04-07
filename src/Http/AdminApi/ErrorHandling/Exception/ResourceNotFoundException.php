<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

final class ResourceNotFoundException extends AbstractRequestException implements RequestExceptionInterface
{
    public function __construct(RequestInterface $request, string $entity, array $primaryKey, ?\Throwable $previous = null)
    {
        parent::__construct(
            $request,
            \sprintf('Entity "%s" by "%s" has not been found', $entity, \json_encode($primaryKey, \JSON_PARTIAL_OUTPUT_ON_ERROR)),
            0,
            $previous
        );
    }
}
