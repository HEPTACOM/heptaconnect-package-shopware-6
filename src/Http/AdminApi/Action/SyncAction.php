<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperation;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception\SyncResultException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction\SyncPayloadInterceptorCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction\SyncPayloadInterceptorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExpectationFailedException;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;

final class SyncAction extends AbstractActionClient implements SyncActionInterface
{
    private SyncPayloadInterceptorCollection $syncPayloadInterceptors;

    private JsonStreamUtility $jsonStream;

    public function __construct(
        ActionClientUtils $actionClientUtils,
        SyncPayloadInterceptorCollection $syncPayloadInterceptors,
        JsonStreamUtility $jsonStream
    ) {
        parent::__construct($actionClientUtils);
        $this->syncPayloadInterceptors = $syncPayloadInterceptors;
        $this->jsonStream = $jsonStream;
    }

    public function sync(SyncPayload $payload): SyncResult
    {
        $payload = $payload->withExpectedPackage('shopware/core', '>=6.5');

        /** @var SyncPayloadInterceptorInterface $syncPayloadInterceptor */
        foreach ($this->syncPayloadInterceptors as $syncPayloadInterceptor) {
            $payload = $syncPayloadInterceptor->intercept($payload);
        }

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
            return new SyncResult([], [], []);
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

        $response = $this->sendAuthenticatedRequest($request);

        try {
            $result = $this->parseResponse($request, $response);
        } catch (ExpectationFailedException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            try {
                $responseData = $this->jsonStream->fromStreamToPayload($response->getBody());
            } catch (\Throwable $ignore) {
                throw $exception;
            }

            throw new SyncResultException(
                $request,
                $response,
                new SyncResult(
                    $responseData['data'] ?? [],
                    $responseData['deleted'] ?? [],
                    $responseData['notFound'] ?? []
                ),
                'Found an error in a sync request',
                1680479000,
                $exception,
            );
        }

        return new SyncResult(
            $result['data'] ?? [],
            $result['deleted'] ?? [],
            $result['notFound'] ?? []
        );
    }
}
