<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnknownError;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ServerErrorValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        if ($error === null) {
            return;
        }

        // 400 on batch operations
        // 500 on single operations
        if ($response->getStatusCode() === 400 || $response->getStatusCode() === 500) {
            $code = $error['code'] ?? '';
            $status = $error['status'] ?? '';
            $title = $error['title'] ?? '';
            $detail = $error['detail'] ?? '';

            if ($status === '500' && $title === 'Internal Server Error') {
                $numCode = 0;

                if (\is_numeric($code)) {
                    $numCode = (int) $code;
                }

                throw new UnknownError($request, $response, \trim($title . \PHP_EOL . $detail), $numCode);
            }
        }
    }
}
