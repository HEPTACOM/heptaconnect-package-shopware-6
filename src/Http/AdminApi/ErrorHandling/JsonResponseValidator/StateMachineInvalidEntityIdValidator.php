<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\StateMachineInvalidEntityIdException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class StateMachineInvalidEntityIdValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';
        $title = $error['title'] ?? '';

        if ($status === '400' && $code === 'SYSTEM__STATE_MACHINE_INVALID_ENTITY_ID' && $title === 'Bad Request') {
            $entityId = $error['meta']['parameters']['entityId'] ?? '';
            $entityName = $error['meta']['parameters']['entityName'] ?? '';

            throw new StateMachineInvalidEntityIdException($request, $response, $entityName, $entityId);
        }
    }
}
