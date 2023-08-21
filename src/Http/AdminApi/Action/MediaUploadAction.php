<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\AbstractMediaUploadPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\MediaUploadActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\MediaUploadByStreamPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\MediaUploadByUrlPayload;

final class MediaUploadAction extends AbstractActionClient implements MediaUploadActionInterface
{
    public function uploadMedia(AbstractMediaUploadPayload $payload): void
    {
        $path = \sprintf('_action/media/%s/upload', $payload->getMediaId());
        $params = [
            'extension' => $payload->getExtension(),
        ];

        if (\is_string($payload->getFileName())) {
            $params['fileName'] = $payload->getFileName();
        }

        if ($payload instanceof MediaUploadByUrlPayload) {
            $request = $this->generateRequest('POST', $path, $params, ['url' => $payload->getUrl()]);
        } elseif ($payload instanceof MediaUploadByStreamPayload) {
            $request = $this->generateRequest('POST', $path, $params)
                ->withHeader('Content-Type', 'application/octet-stream')
                ->withBody($payload->getStream());
        } else {
            throw new \InvalidArgumentException('Content strategy given by $payload is not supported', 1692604930);
        }

        $request = $this->addExpectedPackages($request, $payload);
        $response = $this->sendAuthenticatedRequest($request);

        if ($response->getStatusCode() !== 204) {
            $this->parseResponse($request, $response);
        }
    }
}
