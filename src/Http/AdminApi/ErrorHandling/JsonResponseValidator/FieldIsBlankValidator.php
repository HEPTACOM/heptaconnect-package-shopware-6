<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\FieldIsBlankException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FieldIsBlankValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '400' && $code === 'c1051bb4-d103-4f74-8988-acbcafc7fdc3') {
            $pointer = $error['source']['pointer'] ?? '';

            throw new FieldIsBlankException($request, $pointer, $response->getStatusCode());
        }
    }
}
