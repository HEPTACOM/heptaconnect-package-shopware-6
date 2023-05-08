<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaFileTypeNotSupportedException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MediaFileTypeNotSupportedValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';
        $title = $error['title'] ?? '';

        if ($status === '400' && $code === 'CONTENT__MEDIA_FILE_TYPE_NOT_SUPPORTED' && $title === 'Bad Request') {
            $extension = $error['meta']['parameters']['extension'];
            $mediaId = $error['meta']['parameters']['mediaId'];

            throw new MediaFileTypeNotSupportedException($request, $response, $extension, $mediaId);
        }
    }
}
