<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
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
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\TestBootstrapper;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Factory
{
    private static ?string $shopwareVersion = null;

    public static function getShopwareVersion(): string
    {
        $result = static::$shopwareVersion;

        if ($result === null) {
            $shopwareVersion = self::createActionClass(InfoVersionAction::class)
                ->getVersion(new InfoVersionParams())
                ->getVersion();
            $result = $shopwareVersion;
            static::$shopwareVersion = $result;
        }

        return $result;
    }

    public static function createApiConfigurationStorage(): ApiConfigurationStorageInterface
    {
        return new MemoryApiConfigurationStorage(new ApiConfiguration(
            'password',
            TestBootstrapper::instance()->getAdminApiUrl(),
            TestBootstrapper::instance()->getAdminApiUsername(),
            TestBootstrapper::instance()->getAdminApiPassword(),
            ['write'],
        ));
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
            self::createActionClientUtils(),
            ...$args,
        );
    }

    public static function createAuthenticatedClient(): AuthenticatedHttpClient
    {
        return new AuthenticatedHttpClient(
            BaseFactory::createHttpClient(),
            new Authentication(
                BaseFactory::createSimpleCache(),
                BaseFactory::createJsonStreamUtility(),
                BaseFactory::createRequestFactory(),
                BaseFactory::createHttpClient(),
                self::createApiConfigurationStorage()
            )
        );
    }

    public static function createActionClientUtils(?AuthenticatedHttpClientInterface $client = null): ActionClientUtils
    {
        return new ActionClientUtils(
            $client ?? self::createAuthenticatedClient(),
            BaseFactory::createRequestFactory(),
            self::createApiConfigurationStorage(),
            BaseFactory::createJsonStreamUtility(),
            self::createJsonResponseErrorHandler(),
        );
    }

    public static function createJsonResponseErrorHandler(): JsonResponseErrorHandler
    {
        $validators = new JsonResponseValidatorCollection(BaseFactory::createJsonResponseValidators());
        $validators->push([
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
            new class() implements JsonResponseValidatorInterface {
                public function validate(array $body, ?array $error, RequestInterface $request, ResponseInterface $response): void
                {
                    if ($error !== null) {
                        throw new \RuntimeException('Found error, that is not yet covered by a validator');
                    }
                }
            },
        ]);

        return new JsonResponseErrorHandler(BaseFactory::createJsonStreamUtility(), $validators);
    }
}
