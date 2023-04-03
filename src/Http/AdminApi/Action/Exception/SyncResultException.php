<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Message\RequestInterface;

final class SyncResultException extends AbstractRequestException
{
    private SyncResult $syncResult;

    public function __construct(
        RequestInterface $request,
        SyncResult $syncResult,
        ?string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($request, $message, $code, $previous);
        $this->syncResult = $syncResult;
    }

    public function getSyncResult(): SyncResult
    {
        return $this->syncResult;
    }
}
