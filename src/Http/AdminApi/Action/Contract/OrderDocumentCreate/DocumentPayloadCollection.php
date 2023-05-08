<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<DocumentPayload>
 */
final class DocumentPayloadCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return DocumentPayload::class;
    }
}
