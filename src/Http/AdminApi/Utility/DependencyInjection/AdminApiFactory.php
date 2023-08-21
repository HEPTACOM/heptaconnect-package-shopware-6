<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils as AdminActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration as AdminApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient as AdminAuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticationMemoryCache;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface as AdminApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface as AdminAuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticationInterface as AdminAuthenticationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\DocumentNumberAlreadyExistsValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentFileGeneratorTypeValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentIdValidator;
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
    private AdminApiConfiguration $apiConfiguration;

    private BaseFactory $baseFactory;

    public function __construct(AdminApiConfiguration $apiConfiguration, ?BaseFactory $baseFactory = null)
    {
        $this->apiConfiguration = $apiConfiguration;
        $this->baseFactory = $baseFactory ?? new BaseFactory();
    }

    public function getApiConfiguration(): AdminApiConfiguration
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
            new InvalidDocumentIdValidator(),
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

    public function getAuthenticatedClient(): AdminAuthenticatedHttpClientInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(AdminAuthenticatedHttpClientInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(AdminAuthenticatedHttpClientInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(AdminAuthenticatedHttpClient::class)) {
            return $this->getBaseFactory()->getContainer()->get(AdminAuthenticatedHttpClient::class);
        }

        return new AdminAuthenticatedHttpClient($this->getBaseFactory()->getHttpClient(), $this->getAuthentication());
    }

    public function getAuthentication(): AdminAuthenticationInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(AdminAuthenticationInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(AdminAuthenticationInterface::class);
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

    public function getApiConfigurationStorage(): AdminApiConfigurationStorageInterface
    {
        if ($this->getBaseFactory()->getContainer()->has(AdminApiConfigurationStorageInterface::class)) {
            return $this->getBaseFactory()->getContainer()->get(AdminApiConfigurationStorageInterface::class);
        }

        if ($this->getBaseFactory()->getContainer()->has(MemoryApiConfigurationStorage::class)) {
            return $this->getBaseFactory()->getContainer()->get(MemoryApiConfigurationStorage::class);
        }

        return new MemoryApiConfigurationStorage($this->getApiConfiguration());
    }

    public function getActionClientUtils(): AdminActionClientUtils
    {
        if ($this->getBaseFactory()->getContainer()->has(AdminActionClientUtils::class)) {
            return $this->getBaseFactory()->getContainer()->get(AdminActionClientUtils::class);
        }

        return new AdminActionClientUtils(
            $this->getAuthenticatedClient(),
            $this->getBaseFactory()->getRequestFactory(),
            $this->getApiConfigurationStorage(),
            $this->getBaseFactory()->getJsonStreamUtility(),
            $this->getJsonResponseErrorHandler()
        );
    }
}
