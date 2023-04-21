<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ExtensionUploadAction extends AbstractActionClient implements ExtensionUploadActionInterface
{
    private StreamFactoryInterface $streamFactory;

    public function __construct(
        AuthenticatedHttpClientInterface $client,
        RequestFactoryInterface $requestFactory,
        ApiConfigurationStorageInterface $apiConfigurationStorage,
        JsonStreamUtility $jsonStreamUtility,
        ErrorHandlerInterface $errorHandler,
        StreamFactoryInterface $streamFactory
    ) {
        parent::__construct(
            $client,
            $requestFactory,
            $apiConfigurationStorage,
            $jsonStreamUtility,
            $errorHandler,
        );
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
        $response = $this->getClient()->sendRequest($request);

        $this->parseResponse($request, $response);
    }
}