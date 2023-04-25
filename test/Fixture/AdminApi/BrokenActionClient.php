<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Fixture\AdminApi;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;

final class BrokenActionClient extends AbstractActionClient
{
    public function triggerMethodNotAllowed(): void
    {
        $path = '_info/version';
        $request = $this->generateRequest('DELETE', $path);
        $response = $this->sendAuthenticatedRequest($request);
        $this->parseResponse($request, $response);
    }
}
