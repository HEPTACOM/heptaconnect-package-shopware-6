<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ExtensionNotFoundException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $extensionName;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $extensionName,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('The extension %s is not found', $extensionName);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->extensionName = $extensionName;
    }

    public function getExtensionName(): string
    {
        return $this->extensionName;
    }
}
