<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch\SystemConfigBatchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch\SystemConfigBatchPayload;

final class SystemConfigBatchAction extends AbstractActionClient implements SystemConfigBatchActionInterface
{
    public function batchSystemConfig(SystemConfigBatchPayload $payload): void
    {
        $path = '_action/system-config/batch';

        $request = $this->generateRequest(
            'POST',
            $path,
            [],
            $payload->getValues()
        );

        $response = $this->getClient()->sendRequest($request);
        $this->parseResponse($request, $response);
    }
}
