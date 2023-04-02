<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoResult;

final class InfoAction extends AbstractActionClient implements InfoActionInterface
{
    public function getInfo(InfoParams $params): InfoResult
    {
        $path = '_info/version';
        $request = $this->generateRequest('GET', $path);
        $request = $this->addExpectedPackages($request, $params);
        $response = $this->getClient()->sendRequest($request);
        $result = $this->parseResponse($request, $response);

        return new InfoResult($result['version']);
    }
}
