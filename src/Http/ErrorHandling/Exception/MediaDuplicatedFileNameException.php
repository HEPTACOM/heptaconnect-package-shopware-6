<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MediaDuplicatedFileNameException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $fileName;

    private string $fileExtension;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $fileName,
        string $fileExtension,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('A file with the name "%s.%s" already exists', $fileName, $fileExtension);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->fileName = $fileName;
        $this->fileExtension = $fileExtension;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }
}
