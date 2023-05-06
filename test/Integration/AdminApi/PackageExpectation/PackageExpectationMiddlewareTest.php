<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\PackageExpectation;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\ClientMiddleware\PackageExpectationMiddleware;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\StateMachineInvalidEntityIdValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\ClientMiddleware\PackageExpectationMiddleware
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Contract\PackageExpectationCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\CartMissingOrderRelationValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidUuidValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\UnmappedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\WriteUnexpectedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class PackageExpectationMiddlewareTest extends TestCase
{
    public function testExpectationsFromMiddlewareAndActionArgumentAreMerged(): void
    {
        $realClient = Factory::createAuthenticatedClient();
        $middleware = new PackageExpectationMiddleware(new PackageExpectationCollection([
            new class() implements PackageExpectationInterface {
                public function getPackageExpectation(): array
                {
                    return [
                        'shopware/core: >=6.4.0',
                    ];
                }
            },
        ]), BaseFactory::createUriFactory(), Factory::createApiConfigurationStorage());

        $innerClient = $this->createMock(AuthenticatedHttpClientInterface::class);
        $innerClient->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use ($realClient): ResponseInterface {
                $packages = $request->getHeaderLine('sw-expect-packages');

                static::assertStringContainsString(',', $packages);
                static::assertStringContainsString('<6.5.0', $packages);
                static::assertStringContainsString('>=6.4.0', $packages);

                return $realClient->sendRequest($request);
            });

        $middlewareClient = $this->createMock(AuthenticatedHttpClientInterface::class);
        $middlewareClient->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use ($innerClient, $middleware): ResponseInterface {
                return $middleware->process($request, $innerClient);
            });

        $client = $this->createMock(AuthenticatedHttpClientInterface::class);
        $client->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use ($middlewareClient): ResponseInterface {
                static::assertSame('shopware/core: <6.5.0', $request->getHeaderLine('sw-expect-packages'));

                return $middlewareClient->sendRequest($request);
            });

        $action = new InfoVersionAction(Factory::createActionClientUtils($client));

        $action->getVersion((new InfoVersionParams())->withAddedExpectedPackage('shopware/core', '<6.5.0'));
    }

    public function testExpectationsFromMiddlewareIsSetWhenNoExpectationInActionArgument(): void
    {
        $realClient = Factory::createAuthenticatedClient();
        $middleware = new PackageExpectationMiddleware(new PackageExpectationCollection([
            new class() implements PackageExpectationInterface {
                public function getPackageExpectation(): array
                {
                    return [
                        'shopware/core: >=6.4.0',
                    ];
                }
            },
        ]), BaseFactory::createUriFactory(), Factory::createApiConfigurationStorage());

        $innerClient = $this->createMock(AuthenticatedHttpClientInterface::class);
        $innerClient->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use ($realClient): ResponseInterface {
                $packages = $request->getHeaderLine('sw-expect-packages');

                static::assertStringContainsString('>=6.4.0', $packages);

                return $realClient->sendRequest($request);
            });

        $middlewareClient = $this->createMock(AuthenticatedHttpClientInterface::class);
        $middlewareClient->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use ($innerClient, $middleware): ResponseInterface {
                return $middleware->process($request, $innerClient);
            });

        $client = $this->createMock(AuthenticatedHttpClientInterface::class);
        $client->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use ($middlewareClient): ResponseInterface {
                static::assertSame('', $request->getHeaderLine('sw-expect-packages'));
                static::assertFalse($request->hasHeader('sw-expect-packages'));

                return $middlewareClient->sendRequest($request);
            });

        $action = new InfoVersionAction(Factory::createActionClientUtils($client));

        $action->getVersion(new InfoVersionParams());
    }
}
