<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CartMissingOrderRelationException extends AbstractRequestException
{
    private string $relation;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $relation,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('This required relation "%s" is missing', $relation);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->relation = $relation;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }
}
