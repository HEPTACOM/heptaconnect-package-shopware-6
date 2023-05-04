<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\StoreApi;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\PrefixFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Authentication;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticationMemoryCache;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\JsonResponseValidator\CustomerNotLoggedInValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory as AdminFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\TestBootstrapper;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Factory
{
    private static ?ApiConfiguration $apiConfiguration = null;

    public static function createApiConfigurationStorage(): ApiConfigurationStorageInterface
    {
        return new MemoryApiConfigurationStorage(self::createApiConfiguration());
    }

    /**
     * @template TActionClass of AbstractActionClient
     *
     * @param class-string<TActionClass> $actionClass
     *
     * @return AbstractActionClient&TActionClass
     */
    public static function createActionClass(string $actionClass, ...$args): AbstractActionClient
    {
        return new $actionClass(
            new ActionClientUtils(
                self::createAuthenticatedClient(),
                BaseFactory::createRequestFactory(),
                self::createApiConfigurationStorage(),
                BaseFactory::createJsonStreamUtility(),
                self::createJsonResponseErrorHandler(),
            ),
            ...$args,
        );
    }

    public static function createAuthenticatedClient(): AuthenticatedHttpClient
    {
        return new AuthenticatedHttpClient(
            BaseFactory::createHttpClient(),
            new AuthenticationMemoryCache(new Authentication(self::createApiConfiguration()))
        );
    }

    public static function createApiConfiguration(): ApiConfiguration
    {
        $result = self::$apiConfiguration;
        $salesChannelTypeStorefront = '8a243080f92e4c719546314b577cf82b';

        if ($result === null) {
            $adminUrl = TestBootstrapper::instance()->getAdminApiUrl();
            $baseUrl = (string) BaseFactory::createUriFactory()->createUri($adminUrl)->withPath('');
            $storeApiUrl = TestBootstrapper::instance()->getStoreApiUrl();
            $accessKey = TestBootstrapper::instance()->getStoreApiAccessKey();

            if ($storeApiUrl === null || $accessKey === null) {
                $entitySearch = AdminFactory::createActionClass(EntitySearchAction::class, new CriteriaFormatter());
                $entityCreate = AdminFactory::createActionClass(EntityCreateAction::class);

                $snippetCriteria = (new Criteria())
                    ->withLimit(1)
                    ->withAndFilter(new EqualsFilter('iso', 'en-GB'));
                $snippetSet = $entitySearch->search(new EntitySearchCriteria('snippet-set', $snippetCriteria))->getData()->first();
                $salesChannelCriteria = (new Criteria())
                    ->withLimit(1)
                    ->withAddedAssociation('domains')
                    ->withChangedAssociation(
                        'domains',
                        static fn (Criteria $criteria): Criteria => $criteria->withAndFilter(new PrefixFilter('url', $baseUrl))
                    )
                    ->withAndFilter(new EqualsFilter('type.id', $salesChannelTypeStorefront));
                $salesChannel = $entitySearch->search(new EntitySearchCriteria('sales-channel', $salesChannelCriteria))->getData()->first();
                $url = $salesChannel['domains'][0]['url'] ?? null;

                if ($url === null) {
                    $url = $baseUrl;
                    $entityCreate->create(new EntityCreatePayload('sales-channel-domain', [
                        'currencyId' => $salesChannel->currencyId,
                        'languageId' => $salesChannel->languageId,
                        'salesChannelId' => $salesChannel->id,
                        'snippetSetId' => $snippetSet->id,
                        'hreflangUseOnlyLocale' => false,
                        'url' => $url,
                    ]));
                }

                $storeApiUrl = $url . '/store-api';
                $accessKey = $salesChannel->accessKey;
            }

            $result = new ApiConfiguration($storeApiUrl, $accessKey);
            self::$apiConfiguration = $result;
        }

        return $result;
    }

    public static function createJsonResponseErrorHandler(): JsonResponseErrorHandler
    {
        return new JsonResponseErrorHandler(BaseFactory::createJsonStreamUtility(), [
            ...BaseFactory::createJsonResponseValidators(),
            new CustomerNotLoggedInValidator(),
            new class() implements JsonResponseValidatorInterface {
                public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
                {
                    if ($error !== null) {
                        throw new \RuntimeException('Found error, that is not yet covered by a validator');
                    }
                }
            },
        ]);
    }
}
