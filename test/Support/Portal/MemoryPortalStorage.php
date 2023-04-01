<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

final class MemoryPortalStorage extends Psr16Cache implements PortalStorageInterface
{
    private ArrayAdapter $adapter;

    public function __construct()
    {
        $this->adapter = new ArrayAdapter();
        parent::__construct($this->adapter);
    }

    public function list(): iterable
    {
        return $this->getMultiple(\array_keys($this->adapter->getValues()));
    }
}
