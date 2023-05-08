<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocument;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCreateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate\OrderDocumentCreateResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExpectationFailedException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\JsonResponseValidationCollectionException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\NotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class OrderDocumentCreateAction extends AbstractActionClient implements OrderDocumentCreateActionInterface
{
    private JsonStreamUtility $jsonStream;

    public function __construct(
        ActionClientUtils $actionClientUtils,
        JsonStreamUtility $jsonStream
    ) {
        parent::__construct($actionClientUtils);
        $this->jsonStream = $jsonStream;
    }

    public function createDocuments(OrderDocumentCreatePayload $payload): OrderDocumentCreateResult
    {
        try {
            return $this->createDocumentsSince060414($payload);
        } catch (ExpectationFailedException|NotFoundException $expectationFailedOrRouteNotFoundException) {
            return $this->createDocumentsBefore060500($payload);
        }
    }

    private function createDocumentsSince060414(OrderDocumentCreatePayload $payload): OrderDocumentCreateResult
    {
        $payload_06_04_14 = $payload->withAddedExpectedPackage('shopware/core', '>=6.4.14.0');

        $path = \sprintf('_action/order/document/%s/create', $payload->getDocumentTypeName());
        $body = [];

        foreach ($payload->getDocuments() as $documentPayload) {
            $body[] = $this->removeNullValues([
                'fileType' => $documentPayload->getFileType(),
                'orderId' => $documentPayload->getOrderId(),
                'static' => $documentPayload->getStatic(),
                'config' => $documentPayload->getConfiguration(),
                'referencedDocumentId' => $documentPayload->getReferencedDocumentId(),
            ]);
        }

        $request = $this->generateRequest('POST', $path, [], $body);
        $request = $this->addExpectedPackages($request, $payload_06_04_14);
        $response = $this->sendAuthenticatedRequest($request);

        $result = $this->tryParseResponse($request, $response);

        return new OrderDocumentCreateResult(
            new OrderDocumentCollection(\array_map(
                static fn (array $item): OrderDocument => new OrderDocument($item),
                \array_values($result['result']['data'] ?? [])
            )),
            $result['exceptions']
        );
    }

    private function createDocumentsBefore060500(OrderDocumentCreatePayload $payload): OrderDocumentCreateResult
    {
        $payload = $payload->withAddedExpectedPackage('shopware/core', '<6.5.0.0');
        $success = [];
        $exceptions = [];
        $requestedOrderIds = [];

        foreach ($payload->getDocuments() as $documentPayload) {
            if (\in_array($documentPayload->getOrderId(), $requestedOrderIds, true)) {
                continue;
            }

            $requestedOrderIds[] = $documentPayload->getOrderId();
            $request = $this->generateRequest(
                'POST',
                \sprintf(
                    '_action/order/%s/document/%s',
                    $documentPayload->getOrderId(),
                    $payload->getDocumentTypeName()
                ),
                $this->removeNullValues([
                    'fileType' => $documentPayload->getFileType(),
                ]),
                $this->removeNullValues([
                    'config' => $documentPayload->getConfiguration(),
                    'referenced_document_id' => $documentPayload->getReferencedDocumentId(),
                    'static' => $documentPayload->getStatic(),
                ])
            );
            $request = $this->addExpectedPackages($request, $payload);
            $response = $this->sendAuthenticatedRequest($request);
            $parsedResponse = $this->tryParseResponse($request, $response);
            $responseExceptions = $parsedResponse['exceptions'];

            if ($responseExceptions === []) {
                $success[] = new OrderDocument($parsedResponse['result']);
            }

            $exceptions = \array_merge($exceptions, $responseExceptions);
        }

        return new OrderDocumentCreateResult(new OrderDocumentCollection($success), $exceptions);
    }

    private function removeNullValues(array $body): array
    {
        return \array_filter($body, static fn ($value): bool => $value !== null);
    }

    /**
     * @return array{result: array|null, exceptions: \Throwable[]}
     */
    private function tryParseResponse(RequestInterface $request, ResponseInterface $response): array
    {
        $exceptions = [];

        try {
            $result = $this->parseResponse($request, $response);
        } catch (ExpectationFailedException|NotFoundException $exception) {
            throw $exception;
        } catch (JsonResponseValidationCollectionException $exception) {
            $exceptions = $exception->getExceptions();
        } catch (\Throwable $exception) {
            $exceptions[] = $exception;
        }

        if (!isset($result)) {
            try {
                $result = $this->jsonStream->fromStreamToPayload($response->getBody());
            } catch (\JsonException $e) {
                $result = null;
            }
        }

        return [
            'exceptions' => $exceptions,
            'result' => $result,
        ];
    }
}
