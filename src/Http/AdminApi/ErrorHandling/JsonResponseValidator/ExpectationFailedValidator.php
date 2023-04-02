<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExpectationFailedException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ExpectationFailedValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, array $errors, RequestInterface $request, ResponseInterface $response): void
    {
        if ($response->getStatusCode() === 417) {
            $parameters = $errors[0]['meta']['parameters'] ?? [];

            throw new ExpectationFailedException($request, \implode(\PHP_EOL, $parameters), $response->getStatusCode());
        }
    }
}
