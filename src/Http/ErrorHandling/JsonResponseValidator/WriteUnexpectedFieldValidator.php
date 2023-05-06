<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\WriteUnexpectedFieldException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class WriteUnexpectedFieldValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '400' && $code === 'FRAMEWORK__WRITE_UNEXPECTED_FIELD_ERROR') {
            $field = $error['meta']['parameters']['field'] ?? '';

            throw new WriteUnexpectedFieldException($request, $response, $field);
        }
    }
}
