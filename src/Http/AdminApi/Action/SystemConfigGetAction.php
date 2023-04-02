<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetResult;

final class SystemConfigGetAction extends AbstractActionClient implements SystemConfigGetActionInterface
{
    public function getSystemConfig(SystemConfigGetCriteria $criteria): SystemConfigGetResult
    {
        $path = '_action/system-config';
        $param = [
            'domain' => $criteria->getConfigurationDomain(),
        ];

        if ($criteria->getSalesChannel() !== null) {
            $param['salesChannelId'] = $criteria->getSalesChannel();
        }

        $request = $this->generateRequest('GET', $path, $param);
        $response = $this->getClient()->sendRequest($request);

        return new SystemConfigGetResult($this->parseResponse($request, $response));
    }
}
