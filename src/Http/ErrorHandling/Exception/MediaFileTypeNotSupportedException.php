<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MediaFileTypeNotSupportedException extends AbstractRequestException implements RequestExceptionInterface
{
    private string $extension;

    private string $mediaId;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $extension,
        string $mediaId,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('The file extension "%s" for media object with id "%s" is not supported', $extension, $mediaId);
        parent::__construct($request, $response, $message, $code, $previous);
        $this->extension = $extension;
        $this->mediaId = $mediaId;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getMediaId(): string
    {
        return $this->mediaId;
    }
}
