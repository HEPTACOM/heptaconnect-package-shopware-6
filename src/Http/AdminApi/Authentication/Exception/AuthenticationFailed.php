<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception;

class AuthenticationFailed extends \RuntimeException
{
    public function __construct(int $code, ?\Throwable $previous = null)
    {
        parent::__construct('Could not request a valid access token', $code, $previous);
    }
}
