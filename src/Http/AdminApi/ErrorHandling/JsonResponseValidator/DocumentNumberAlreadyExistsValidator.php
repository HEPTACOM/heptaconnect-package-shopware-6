<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\DocumentNumberAlreadyExistsException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class DocumentNumberAlreadyExistsValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';
        $title = $error['title'] ?? '';

        if ($status === '400' && $code === 'DOCUMENT__NUMBER_ALREADY_EXISTS' && $title === 'Bad Request') {
            $number = $error['meta']['parameters']['number'];

            throw new DocumentNumberAlreadyExistsException($request, $response, $number);
        }
    }
}
