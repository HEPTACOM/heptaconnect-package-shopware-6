<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\UnknownError;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ServerErrorValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, array $errors, RequestInterface $request, ResponseInterface $response): void
    {
        if ($response->getStatusCode() === 500) {
            foreach ($errors as $error) {
                $code = $error['code'] ?? '';
                $status = $error['status'] ?? '';
                $title = $error['title'] ?? '';
                $detail = $error['detail'] ?? '';

                if ($code === '0' && $status === '500' && $title === 'Internal Server Error') {
                    throw new UnknownError($request, \trim($title . \PHP_EOL . $detail));
                }
            }
        }
    }
}
