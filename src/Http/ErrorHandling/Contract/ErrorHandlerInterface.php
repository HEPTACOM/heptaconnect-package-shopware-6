<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\JsonResponseValidationCollectionException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ErrorHandlerInterface
{
    /**
     * Parses the response and looks for known errors, that can be interpreted as a structure PHP exception.
     * When no error is found, no exception is thrown.
     *
     * @throws \Throwable
     * @throws JsonResponseValidationCollectionException
     */
    public function throwException(RequestInterface $request, ResponseInterface $response): void;
}
