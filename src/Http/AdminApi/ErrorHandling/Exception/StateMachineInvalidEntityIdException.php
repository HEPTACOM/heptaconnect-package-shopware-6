<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class StateMachineInvalidEntityIdException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $entityName;

    private string $entityId;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $entityName,
        string $entityId,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Unable to read entity "%s" with id "%s".', $entityName, $entityId);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->entityName = $entityName;
        $this->entityId = $entityId;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getEntityId(): string
    {
        return $this->entityId;
    }
}
