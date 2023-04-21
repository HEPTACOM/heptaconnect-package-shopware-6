<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PluginNoPluginFoundInZipException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $archive;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $archive,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('The given archive %s does not contain a plugin', $archive);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->archive = $archive;
    }

    public function getArchive(): string
    {
        return $this->archive;
    }
}
