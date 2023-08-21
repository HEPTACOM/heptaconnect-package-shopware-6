<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentGet;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidDocumentIdException;

interface OrderDocumentGetActionInterface
{
    /**
     * Read a document for an order.
     *
     * @throws InvalidDocumentIdException if the given documentId or deepLinkCode do not match a document
     * @throws \Throwable
     */
    public function getDocument(OrderDocumentGetCriteria $criteria): OrderDocumentGetResult;
}
