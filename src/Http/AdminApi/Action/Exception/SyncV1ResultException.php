<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SyncV1\SyncResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class SyncV1ResultException extends AbstractRequestException
{
    private SyncResult $syncResult;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        SyncResult $syncResult,
        ?string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($request, $response, $message, $code, $previous);
        $this->syncResult = $syncResult;
    }

    public function getSyncResult(): SyncResult
    {
        return $this->syncResult;
    }
}
