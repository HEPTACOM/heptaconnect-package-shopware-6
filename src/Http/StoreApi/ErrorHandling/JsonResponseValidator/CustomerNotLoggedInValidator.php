<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\Exception\CustomerNotLoggedInException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CustomerNotLoggedInValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '403' && $code === 'CHECKOUT__CUSTOMER_NOT_LOGGED_IN') {
            $detail = $error['detail'] ?? '';

            throw new CustomerNotLoggedInException($request, $response, $detail);
        }
    }
}
