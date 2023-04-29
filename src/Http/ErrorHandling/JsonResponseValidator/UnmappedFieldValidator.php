<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnmappedFieldException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class UnmappedFieldValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '400' && $code === 'FRAMEWORK__UNMAPPED_FIELD') {
            $entity = $error['meta']['parameters']['entity'] ?? '';
            $field = $error['meta']['parameters']['field'] ?? '';

            throw new UnmappedFieldException($request, $response, $entity, $field, $response->getStatusCode());
        }
    }
}
