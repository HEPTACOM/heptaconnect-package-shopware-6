<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet\OrderDocumentGetActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet\OrderDocumentGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet\OrderDocumentGetResult;

final class OrderDocumentGetAction extends AbstractActionClient implements OrderDocumentGetActionInterface
{
    public function getDocument(OrderDocumentGetCriteria $criteria): OrderDocumentGetResult
    {
        $path = \sprintf('_action/document/%s/%s', $criteria->getDocumentId(), $criteria->getDeepLinkCode());
        $request = $this->generateRequest('GET', $path);
        $request = $this->addExpectedPackages($request, $criteria);
        $response = $this->sendAuthenticatedRequest($request);

        if ($response->getStatusCode() !== 200) {
            $this->parseResponse($request, $response);
        }

        $filename = null;

        if (\preg_match('/^(?:.*;\s*)?filename=(.*)(?:;.*)?$/', $response->getHeaderLine('content-disposition'), $matches) === 1) {
            $filename = $matches[1];
        }

        $mimeType = $response->getHeaderLine('content-type');

        return new OrderDocumentGetResult($response->getBody(), $mimeType, $filename);
    }
}
