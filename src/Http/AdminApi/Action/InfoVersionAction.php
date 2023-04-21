<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionResult;

final class InfoVersionAction extends AbstractActionClient implements InfoVersionActionInterface
{
    public function getVersion(InfoVersionParams $params): InfoVersionResult
    {
        $path = '_info/version';
        $request = $this->generateRequest('GET', $path);
        $request = $this->addExpectedPackages($request, $params);
        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        return new InfoVersionResult($result['version']);
    }
}
