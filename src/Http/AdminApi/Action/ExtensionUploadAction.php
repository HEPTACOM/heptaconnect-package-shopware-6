<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClient;
use Psr\Http\Message\StreamFactoryInterface;

final class ExtensionUploadAction extends AbstractActionClient implements ExtensionUploadActionInterface
{
    private StreamFactoryInterface $streamFactory;

    public function __construct(ActionClient $actionClient, StreamFactoryInterface $streamFactory)
    {
        parent::__construct($actionClient);
        $this->streamFactory = $streamFactory;
    }

    public function uploadExtension(ExtensionUploadPayload $payload): void
    {
        $boundary = '----' . \bin2hex(\random_bytes(16));
        $body = \implode(\PHP_EOL, [
            '--' . $boundary,
            \sprintf('Content-Disposition: form-data; name="file"; filename="%s"', $payload->getZipFileName()),
            'Content-Type: application/zip',
            '',
            (string) $payload->getZipFileStream(),
            '',
            '--' . $boundary . '--',
            '',
        ]);

        $path = '_action/extension/upload';
        $request = $this->generateRequest('POST', $path);
        $request = $this->addExpectedPackages($request, $payload);
        $request = $request
            ->withHeader('Content-Type', 'multipart/form-data; boundary=' . $boundary)
            ->withBody($this->streamFactory->createStream($body));
        $response = $this->sendAuthenticatedRequest($request);

        $this->parseResponse($request, $response);
    }
}
