<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidLimitQueryException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class InvalidLimitQueryValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '400' && $code === 'FRAMEWORK__INVALID_LIMIT_QUERY') {
            $pointer = $error['source']['pointer'] ?? '';
            $message = $error['detail'] ?? '';

            throw new InvalidLimitQueryException($request, $response, $message, $pointer);
        }
    }
}
