<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Utility\DependencyInjection;

use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\ErrorHandlerInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\CartMissingOrderRelationValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidUuidValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MediaDuplicatedFileNameValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MediaFileTypeNotSupportedValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\NotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ScopeNotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ServerErrorValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\UnmappedFieldValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\WriteUnexpectedFieldValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\ApiConfiguration as StoreApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticatedHttpClient as StoreAuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Authentication as StoreAuthentication;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\AuthenticationMemoryCache;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\ApiConfigurationStorageInterface as StoreApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticatedHttpClientInterface as StoreAuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\Contract\AuthenticationInterface as StoreAuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Authentication\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\ErrorHandling\JsonResponseValidator\CustomerNotLoggedInValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory;

/**
 * Use this class to provide shared services for the StoreAPI, when no dependency injection component is used.
 * When a service needs to be replaced, use the container in the @see BaseFactory to provide services by FQCN.
 */
final class StoreApiFactory
{
    private StoreApiConfiguration $apiConfiguration;

    private BaseFactory $baseFactory;

    public function __construct(StoreApiConfiguration $apiConfiguration, ?BaseFactory $baseFactory = null)
    {
        $this->apiConfiguration = $apiConfiguration;
        $this->baseFactory = $baseFactory ?? new BaseFactory();
    }

    public function getApiConfiguration(): StoreApiConfiguration
    {
        return $this->apiConfiguration;
    }

    public function getBaseFactory(): BaseFactory
    {
        return $this->baseFactory;
    }

    public function getJsonResponseValidatorCollection(): JsonResponseValidatorCollection
    {
        if ($this->getBaseFactory()->getContainer()->has(JsonResponseValidatorCollection::class . '.store_api')) {
            return $this->getBaseFactory()->getContainer()->get(JsonResponseValidatorCollection::class . '.store_api');
        }

        if ($this->getBaseFactory()->getContainer()->has(JsonResponseValidatorCollection::class)) {
            return $this->getBaseFactory()->getContainer()->get(JsonResponseValidatorCollection::class);
        }

        return new JsonResponseValidatorCollection([
            new CartMissingOrderRelationValidator(),
            new FieldIsBlankValidator(),
            new ResourceNotFoundValidator(),
            new ScopeNotFoundValidator(),
            new InvalidLimitQueryValidator(),
            new InvalidUuidValidator(),
            new UnmappedFieldValidator(),
            new NotFoundValidator(),
            new MethodNotAllowedValidator(),
            new WriteUnexpectedFieldValidator(),
            new MediaFileTypeNotSupportedValidator(),
            new MediaDuplicatedFileNameValidator(),
            // exclusive
            new CustomerNotLoggedInValidator(),
            // not exclusive, but should be last resort
            new ServerErrorValidator(),
        ]);
    }

    public function getJsonResponseErrorHandler(): ErrorHandlerInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(ErrorHandlerInterface::class . '.store_api')) {
            return $this->getBaseFactory()->getContainer()->get(ErrorHandlerInterface::class . '.store_api');
        }

        if ($this->getBaseFactory()->getContainer()->has(ErrorHandlerInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(ErrorHandlerInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(JsonResponseErrorHandler::class . '.store_api')) {
            return $this->getBaseFactory()->getContainer()->get(JsonResponseErrorHandler::class . '.store_api');
        }

        if ($this->getBaseFactory()->getContainer()->has(JsonResponseErrorHandler::class)) {
            return $this->getBaseFactory()->getContainer()->get(JsonResponseErrorHandler::class);
        }

        return new JsonResponseErrorHandler(
            $this->getBaseFactory()->getJsonStreamUtility(),
            $this->getJsonResponseValidatorCollection(),
        );
    }

    public function getAuthentication(): StoreAuthenticationInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(StoreAuthenticationInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(StoreAuthenticationInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(AuthenticationMemoryCache::class)) {
            return $this->getBaseFactory()->getContainer()->get(AuthenticationMemoryCache::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(StoreAuthentication::class)) {
            return $this->getBaseFactory()->getContainer()->get(StoreAuthentication::class);
        }

        return new AuthenticationMemoryCache(new StoreAuthentication($this->getApiConfigurationStorage()->getConfiguration()));
    }

    public function getAuthenticatedClient(): StoreAuthenticatedHttpClientInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(StoreAuthenticatedHttpClientInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(StoreAuthenticatedHttpClientInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(StoreAuthenticatedHttpClient::class)) {
            return $this->getBaseFactory()->getContainer()->get(StoreAuthenticatedHttpClient::class);
        }

        return new StoreAuthenticatedHttpClient($this->getBaseFactory()->getHttpClient(), $this->getAuthentication());
    }

    public function getApiConfigurationStorage(): StoreApiConfigurationStorageInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(StoreApiConfigurationStorageInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(StoreApiConfigurationStorageInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(MemoryApiConfigurationStorage::class)) {
            return $this->getBaseFactory()->getContainer()->get(MemoryApiConfigurationStorage::class);
        }

        return new MemoryApiConfigurationStorage($this->getApiConfiguration());
    }

    public function getActionClientUtils(): ActionClientUtils
    {
        if ($this->getBaseFactory()->getContainer()->has(ActionClientUtils::class)) {
            return $this->getBaseFactory()->getContainer()->get(ActionClientUtils::class);
        }

        return new ActionClientUtils(
            $this->getAuthenticatedClient(),
            $this->getBaseFactory()->getRequestFactory(),
            $this->getApiConfigurationStorage(),
            $this->getBaseFactory()->getJsonStreamUtility(),
            $this->getJsonResponseErrorHandler()
        );
    }
}
