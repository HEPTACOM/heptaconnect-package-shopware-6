<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ErrorHandlerInterface
{
    /**
     * Parses the response and looks for known errors, that can be interpreted as a structure PHP exception.
     * When no error is found, no exception is thrown.
     *
     * @throws \Throwable
     */
    public function throwException(RequestInterface $request, ResponseInterface $response): void;
}
