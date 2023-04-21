<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\ApiConfigurationStorageInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Contract\JsonResponseValidatorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseErrorHandler;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\NotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ServerErrorValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator;
use Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\MemoryApiConfigurationStorage;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Portal\MemoryPortalStorage;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractActionTestCase extends TestCase
{
    protected function createApiConfigurationStorage(): ApiConfigurationStorageInterface
    {
        return MemoryApiConfigurationStorage::createBootstrapped();
    }

    protected function createClient(): AuthenticatedHttpClientInterface
    {
        $client = Psr18ClientDiscovery::find();

        return new AuthenticatedHttpClient(
            $client,
            new PortalNodeStorageAuthenticationStorage(
                new MemoryPortalStorage(),
                $this->createJsonStreamUtility(),
                $this->createRequestFactory(),
                $client,
                $this->createApiConfigurationStorage()
            )
        );
    }

    /**
     * @template TActionClass of AbstractActionClient
     *
     * @param class-string<TActionClass> $actionClass
     *
     * @return AbstractActionClient&TActionClass
     */
    protected function createAction(string $actionClass, ...$args): AbstractActionClient
    {
        $jsonStreamUtility = $this->createJsonStreamUtility();

        return new $actionClass(
            $this->createClient(),
            $this->createRequestFactory(),
            $this->createApiConfigurationStorage(),
            $jsonStreamUtility,
            $this->createJsonResponseErrorHandler($jsonStreamUtility),
            ...$args,
        );
    }

    protected function createRequestFactory(): RequestFactoryInterface
    {
        return Psr17FactoryDiscovery::findRequestFactory();
    }

    protected function createJsonStreamUtility(): JsonStreamUtility
    {
        return new JsonStreamUtility(Psr17FactoryDiscovery::findStreamFactory());
    }

    protected function createJsonResponseErrorHandler(?JsonStreamUtility $jsonStreamUtility = null): JsonResponseErrorHandler
    {
        $jsonStreamUtility ??= $this->createJsonStreamUtility();

        return new JsonResponseErrorHandler($jsonStreamUtility, [
            new ExpectationFailedValidator(),
            new ServerErrorValidator(),
            new FieldIsBlankValidator(),
            new ExtensionNotFoundValidator(),
            new ExtensionInstallValidator(),
            new ResourceNotFoundValidator(),
            new WriteTypeIntendErrorValidator(),
            new PluginNotInstalledValidator(),
            new PluginNotFoundValidator(),
            new PluginNoPluginFoundInZipValidator(),
            new PluginNotActivatedValidator(),
            new InvalidLimitQueryValidator(),
            new NotFoundValidator(),
            new MethodNotAllowedValidator(),
            new InvalidTypeValidator(),
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
