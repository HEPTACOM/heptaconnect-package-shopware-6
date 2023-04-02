<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost\SystemConfigPostActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost\SystemConfigPostPayload;

final class SystemConfigPostAction extends AbstractActionClient implements SystemConfigPostActionInterface
{
    public function postSystemConfig(SystemConfigPostPayload $payload): void
    {
        $path = '_action/system-config';
        $params = [];

        if (\is_string($payload->getSalesChannel())) {
            $params['salesChannelId'] = $payload->getSalesChannel();
        }

        $request = $this->generateRequest(
            'POST',
            $path,
            $params,
            $payload->getValues()
        );

        $response = $this->getClient()->sendRequest($request);
        $this->parseResponse($request, $response);
    }
}
