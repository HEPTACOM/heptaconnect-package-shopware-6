<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\ExpectedPackagesAwareInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

/**
 * A class holding dependencies and utility methods to easily create JSON requests and parse JSON responses with meaningful exceptions.
 */
final class ActionClientUtils extends AbstractShopwareClientUtils
{
    private ApiConfigurationStorageInterface $apiConfigurationStorage;

    public function __construct(
        AuthenticatedHttpClientInterface $client,
        RequestFactoryInterface $requestFactory,
        ApiConfigurationStorageInterface $apiConfigurationStorage,
        JsonStreamUtility $jsonStreamUtility,
        ErrorHandlerInterface $errorHandler
    ) {
        parent::__construct($client, $requestFactory, $jsonStreamUtility, $errorHandler);
        $this->apiConfigurationStorage = $apiConfigurationStorage;
    }

    public function addExpectedPackages(
        RequestInterface $request,
        ExpectedPackagesAwareInterface $expectedPackagesAware
    ): RequestInterface {
        $expectedPackages = [];

        foreach ($expectedPackagesAware->getExpectedPackageVersionConstraints() as $package => $constraints) {
            foreach ($constraints as $constraint) {
                $expectedPackages[] = \sprintf('%s: %s', $package, $constraint);
            }
        }

        if ($expectedPackages !== []) {
            $expectedPackages = \array_unique($expectedPackages);

            $request = $request->withHeader('sw-expect-packages', \implode(',', $expectedPackages));
        }

        return $request;
    }

    protected function getBaseUrl(): string
    {
        return $this->apiConfigurationStorage->getConfiguration()->getUrl();
    }
}
