<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Client\RequestExceptionInterface;

final class CustomerNotLoggedInException extends AbstractRequestException implements RequestExceptionInterface
{
}
