<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperation;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationResultCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncResult;

final class SyncAction extends AbstractActionClient implements SyncActionInterface
{
    public function sync(SyncPayload $payload): SyncResult
    {
        $path = '_action/sync';
        $body = [];

        /** @var SyncOperation $syncOperation */
        foreach ($payload->getSyncOperations() as $syncOperation) {
            $syncPayload = $syncOperation->getPayload();

            if ($syncPayload === []) {
                continue;
            }

            $body[$syncOperation->getKey()] = [
                'entity' => $syncOperation->getEntity(),
                'action' => $syncOperation->getAction(),
                'payload' => $syncPayload,
            ];
        }

        if ($body === []) {
            return new SyncResult(new SyncOperationResultCollection());
        }

        $request = $this->generateRequest('POST', $path, [], $body);
        $request = $this->addExpectedPackages($request, $payload);
        $indexingBehavior = $payload->getIndexingBehavior();

        if ($indexingBehavior !== null) {
            $request = $request->withHeader('indexing-behavior', $indexingBehavior);
        }

        $indexingSkip = $payload->getIndexingSkip();

        if ($indexingSkip !== []) {
            $request = $request->withHeader('indexing-skip', \implode(',', $indexingSkip));
        }

        $singleOperation = $payload->getSingleOperation();

        if ($singleOperation !== null) {
            $request = $request->withHeader('single-operation', (int) $singleOperation);
        }

        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        $success = $result['success'];
        $data = $result['data'];

        return new SyncResult(new SyncOperationResultCollection(\array_map(
            static fn ($data, $key): SyncOperationResult => new SyncOperationResult((string) $key, $data['result']),
            \array_values($data),
            \array_keys($data),
        )));
    }
}
