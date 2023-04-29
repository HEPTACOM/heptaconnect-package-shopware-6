<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotInstalledException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PluginNotInstalledValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';
        $title = $error['title'] ?? '';

        if ($status === '500' && $code === 'FRAMEWORK__PLUGIN_NOT_INSTALLED' && $title === 'Internal Server Error') {
            $pluginName = $error['meta']['parameters']['plugin'];

            throw new PluginNotInstalledException($request, $response, $pluginName);
        }
    }
}
