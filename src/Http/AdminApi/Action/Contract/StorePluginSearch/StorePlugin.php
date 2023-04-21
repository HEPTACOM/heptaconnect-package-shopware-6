<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch;

/**
 * @extends \ArrayObject<string, scalar|array|null>
 *
 * @property bool        $active
 * @property string|null $description
 * @property string|null $label
 * @property string      $name
 * @property string      $source
 * @property string      $type
 * @property string      $version
 */
final class StorePlugin extends \ArrayObject
{
    public function __construct(array $data)
    {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }
}
