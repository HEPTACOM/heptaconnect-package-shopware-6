<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Message\RequestInterface;

final class EntityReferenceLocationFormatInvalidException extends AbstractRequestException
{
    private string $location;

    public function __construct(RequestInterface $request, string $location, int $code = 0, ?\Throwable $previous = null)
    {
        $message = \sprintf('Expected entity reference in location response header but location is :%s', $location);
        parent::__construct($request, $message, $code, $previous);
        $this->location = $location;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}
