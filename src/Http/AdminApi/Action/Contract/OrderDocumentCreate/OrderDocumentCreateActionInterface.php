<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\DocumentNumberAlreadyExistsException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidDocumentFileGeneratorTypeException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaDuplicatedFileNameException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaFileTypeNotSupportedException;

interface OrderDocumentCreateActionInterface
{
    /**
     * Create documents for orders.
     * This batch operation can partially be successful.
     * Therefore check exceptions stored in the result.
     *
     * @throws DocumentNumberAlreadyExistsException      if the document number already exists
     * @throws InvalidDocumentFileGeneratorTypeException if no valid generator is found for the request file type
     * @throws MediaDuplicatedFileNameException          if the document number already exists
     * @throws MediaFileTypeNotSupportedException        if no valid generator is found for the request file type
     * @throws \Throwable
     */
    public function createDocuments(OrderDocumentCreatePayload $payload): OrderDocumentCreateResult;
}
