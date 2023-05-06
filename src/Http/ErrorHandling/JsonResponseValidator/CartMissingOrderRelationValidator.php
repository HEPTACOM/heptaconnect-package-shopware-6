<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\CartMissingOrderRelationException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CartMissingOrderRelationValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '400' && $code === 'CHECKOUT__CART_MISSING_ORDER_RELATION') {
            $relation = $error['meta']['parameters']['relation'] ?? '';

            throw new CartMissingOrderRelationException($request, $response, $relation);
        }
    }
}
