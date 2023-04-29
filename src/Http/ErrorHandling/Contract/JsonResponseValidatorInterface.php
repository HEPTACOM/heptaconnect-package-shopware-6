<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * An instance of this interface should only look out for a single kind of error.
 */
interface JsonResponseValidatorInterface
{
    /**
     * Validate the parsed JSON response and throw an exception if an error in the response is detected.
     *
     * @throws \Throwable
     */
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void;
}
