<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\MethodNotAllowedException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MethodNotAllowedValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';
        $title = $error['title'] ?? '';

        if ($status === '405' && $code === '0' && $title === 'Method Not Allowed') {
            $detail = $error['detail'] ?? '';

            throw new MethodNotAllowedException($request, $detail);
        }
    }
}
