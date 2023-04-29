<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;

final class MethodNotAllowedException extends AbstractRequestException implements RequestExceptionInterface
{
}
