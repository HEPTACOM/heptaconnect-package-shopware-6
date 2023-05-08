<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaDuplicatedFileNameException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MediaDuplicatedFileNameValidator implements JsonResponseValidatorInterface
{
    public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
    {
        $code = $error['code'] ?? '';
        $status = $error['status'] ?? '';

        if ($status === '500' && $code === 'CONTENT__MEDIA_DUPLICATED_FILE_NAME') {
            $fileName = $error['meta']['parameters']['fileName'];
            $fileExtension = $error['meta']['parameters']['fileExtension'];

            throw new MediaDuplicatedFileNameException($request, $response, $fileName, $fileExtension);
        }
    }
}
