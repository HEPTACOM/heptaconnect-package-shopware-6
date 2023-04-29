<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\ResourceNotFoundException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ResourceNotFoundValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '404' && $code === 'FRAMEWORK__RESOURCE_NOT_FOUND') {
            $primaryKey = $error['meta']['parameters']['primaryKey'] ?? [];
            $type = $error['meta']['parameters']['type'] ?? '';

            throw new ResourceNotFoundException($request, $response, $type, $primaryKey);
        }
    }
}
