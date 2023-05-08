<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate;

/**
 * @extends \ArrayObject<string, scalar|array|null>
 *
 * @property string      $documentId
 * @property string      $documentDeepLink
 * @property string|null $documentMediaId
 */
final class OrderDocument extends \ArrayObject
{
    public function __construct(array $data)
    {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }
}
