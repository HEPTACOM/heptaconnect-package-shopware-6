<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility;

use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic\GenericActionInterface as StoreActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\Generic\GenericPayload as StorePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericResult;

/**
 * Facade to send common HTTP methods to prototype request or build not yet existing actions.
 * If you want to intercept a process, use the action services.
 */
final class GenericClient extends AbstractGenericClient
{
    private StoreActionInterface $generic;

    public function __construct(StoreActionInterface $generic)
    {
        $this->generic = $generic;
    }

    protected function generatePayload(string $path, string $method): AbstractGenericPayload
    {
        return new StorePayload($path, $method);
    }

    /**
     * @param StorePayload $payload
     */
    protected function sendGenericRequest(AbstractGenericPayload $payload): AbstractGenericResult
    {
        return $this->generic->sendGenericRequest($payload);
    }
}
