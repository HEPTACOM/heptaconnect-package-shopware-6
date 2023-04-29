<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\WriteTypeIntendException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class WriteTypeIntendErrorValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '500' && $code === 'FRAMEWORK__WRITE_TYPE_INTEND_ERROR') {
            $actualClass = $error['meta']['parameters']['actualClass'] ?? '';
            $definition = $error['meta']['parameters']['definition'] ?? '';
            $expectedClass = $error['meta']['parameters']['expectedClass'] ?? '';

            throw new WriteTypeIntendException($request, $response, $definition, $expectedClass, $actualClass);
        }
    }
}
