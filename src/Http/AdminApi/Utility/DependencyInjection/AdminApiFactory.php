<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticationMemoryCache;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\DocumentNumberAlreadyExistsValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentFileGeneratorTypeValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\StateMachineInvalidEntityIdValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator;
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
use Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory;

/**
 * Use this class to provide shared services for the AdminAPI, when no dependency injection component is used.
 * When a service needs to be replaced, use the container in the @see BaseFactory to provide services by FQCN.
 */
final class AdminApiFactory
{
    private ApiConfiguration $apiConfiguration;

    private BaseFactory $baseFactory;

    public function __construct(ApiConfiguration $apiConfiguration, ?BaseFactory $baseFactory = null)
    {
        $this->apiConfiguration = $apiConfiguration;
        $this->baseFactory = $baseFactory ?? new BaseFactory();
    }

    public function getApiConfiguration(): ApiConfiguration
    {
        return $this->apiConfiguration;
    }

    public function getBaseFactory(): BaseFactory
    {
        return $this->baseFactory;
    }

    public function getJsonResponseValidatorCollection(): JsonResponseValidatorCollection
    {
        if ($this->getBaseFactory()->getContainer()->has(JsonResponseValidatorCollection::class . '.admin_api')) {
            return $this->getBaseFactory()->getContainer()->get(JsonResponseValidatorCollection::class . '.admin_api');
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
            new ExpectationFailedValidator(),
            new ExtensionNotFoundValidator(),
            new ExtensionInstallValidator(),
            new WriteTypeIntendErrorValidator(),
            new PluginNotInstalledValidator(),
            new PluginNotFoundValidator(),
            new PluginNoPluginFoundInZipValidator(),
            new PluginNotActivatedValidator(),
            new InvalidTypeValidator(),
            new StateMachineInvalidEntityIdValidator(),
            new InvalidDocumentFileGeneratorTypeValidator(),
            new DocumentNumberAlreadyExistsValidator(),
            // not exclusive, but should be last resort
            new ServerErrorValidator(),
        ]);
    }

    public function getJsonResponseErrorHandler(): ErrorHandlerInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(ErrorHandlerInterface::class . '.admin_api')) {
            return $this->getBaseFactory()->getContainer()->get(ErrorHandlerInterface::class . '.admin_api');
        }

        if ($this->getBaseFactory()->getContainer()->has(ErrorHandlerInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(ErrorHandlerInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(JsonResponseErrorHandler::class . '.admin_api')) {
            return $this->getBaseFactory()->getContainer()->get(JsonResponseErrorHandler::class . '.admin_api');
        }

        if ($this->getBaseFactory()->getContainer()->has(JsonResponseErrorHandler::class)) {
            return $this->getBaseFactory()->getContainer()->get(JsonResponseErrorHandler::class);
        }

        return new JsonResponseErrorHandler(
            $this->getBaseFactory()->getJsonStreamUtility(),
            $this->getJsonResponseValidatorCollection(),
        );
    }

    public function getAuthenticatedClient(): AuthenticatedHttpClientInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(AuthenticatedHttpClientInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(AuthenticatedHttpClientInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(AuthenticatedHttpClient::class)) {
            return $this->getBaseFactory()->getContainer()->get(AuthenticatedHttpClient::class);
        }

        return new AuthenticatedHttpClient($this->getBaseFactory()->getHttpClient(), $this->getAuthentication());
    }

    public function getAuthentication(): AuthenticationInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(AuthenticationInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(AuthenticationInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(AuthenticationMemoryCache::class)) {
            return $this->getBaseFactory()->getContainer()->get(AuthenticationMemoryCache::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(Authentication::class)) {
            return $this->getBaseFactory()->getContainer()->get(Authentication::class);
        }

        return new AuthenticationMemoryCache(
            new Authentication(
                $this->getBaseFactory()->getSimpleCache(),
                $this->getBaseFactory()->getJsonStreamUtility(),
                $this->getBaseFactory()->getRequestFactory(),
                $this->getBaseFactory()->getHttpClient(),
                $this->getApiConfigurationStorage()
            )
        );
    }

    public function getApiConfigurationStorage(): ApiConfigurationStorageInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(ApiConfigurationStorageInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(ApiConfigurationStorageInterface::class);
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
