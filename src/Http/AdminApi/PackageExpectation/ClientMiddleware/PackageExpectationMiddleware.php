<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\ClientMiddleware;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientMiddlewareInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;

final class PackageExpectationMiddleware implements HttpClientMiddlewareInterface
{
    private ?StringCollection $packageConstraints = null;

    private ?string $apiUrlAuthority = null;

    private PackageExpectationCollection $packageExpectations;

    private UriFactoryInterface $uriFactory;

    private ApiConfigurationStorageInterface $apiConfigurationStorage;

    public function __construct(
        PackageExpectationCollection $packageExpectations,
        UriFactoryInterface $uriFactory,
        ApiConfigurationStorageInterface $apiConfigurationStorage
    ) {
        $this->packageExpectations = $packageExpectations;
        $this->uriFactory = $uriFactory;
        $this->apiConfigurationStorage = $apiConfigurationStorage;
    }

    public function process(RequestInterface $request, ClientInterface $handler): ResponseInterface
    {
        $expectedAuthority = $this->getExpectedAuthority();
        $actualAuthority = $request->getUri()->getAuthority();
        $isRequestTargetingShopware = $expectedAuthority === $actualAuthority;
        $isRequestTargetingAdminApi = \str_contains($request->getUri()->getPath(), '/api/');

        if ($isRequestTargetingShopware && $isRequestTargetingAdminApi) {
            $packageConstraints = $this->getPackageConstraints();

            if (!$packageConstraints->isEmpty()) {
                $previousValue = \trim($request->getHeaderLine('sw-expect-packages'));

                if ($previousValue !== '') {
                    $previousValue .= ',';
                }

                $request = $request->withHeader('sw-expect-packages', $previousValue . $packageConstraints->join(','));
            }
        }

        return $handler->sendRequest($request);
    }

    private function getExpectedAuthority(): string
    {
        $apiUrlAuthority = $this->apiUrlAuthority;

        if ($apiUrlAuthority === null) {
            $apiUrlAuthority = $this->uriFactory->createUri(
                $this->apiConfigurationStorage->getConfiguration()->getUrl()
            )->getAuthority();
            $this->apiUrlAuthority = $apiUrlAuthority;
        }

        return $apiUrlAuthority;
    }

    private function getPackageConstraints(): StringCollection
    {
        $packageConstraints = $this->packageConstraints;

        if ($packageConstraints === null) {
            $packageConstraints = $this->packageExpectations->getMergedExpectedPackages();
            $this->packageConstraints = $packageConstraints;
        }

        return $packageConstraints;
    }
}
