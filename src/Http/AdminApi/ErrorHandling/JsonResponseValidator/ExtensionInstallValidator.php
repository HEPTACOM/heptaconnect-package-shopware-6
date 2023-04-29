<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExtensionInstallException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ExtensionInstallValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';
        $title = $error['title'] ?? '';

        if ($status === '500' && $code === 'FRAMEWORK__EXTENSION_INSTALL_EXCEPTION' && $title === 'Internal Server Error') {
            $detail = $error['detail'] ?? '';

            throw new ExtensionInstallException($request, $response, $detail);
        }
    }
}
