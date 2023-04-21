<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PluginNotInstalledException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $pluginName;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $pluginName,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('The plugin %s is not installed', $pluginName);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->pluginName = $pluginName;
    }

    public function getPluginName(): string
    {
        return $this->pluginName;
    }
}
