<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUninstallAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\NotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\AbstractExtensionPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUninstallAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExtensionNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class ExtensionUninstallActionTest extends TestCase
{
    public function testPluginDoesNotExists(): void
    {
        $action = Factory::createActionClass(ExtensionUninstallAction::class);

        static::expectException(PluginNotFoundException::class);

        $action->uninstallExtension(new ExtensionUninstallPayload('plugin', 'PluginThatDoesNotExists'));
    }

    public function testAppDoesNotExists(): void
    {
        static::expectNotToPerformAssertions();

        $action = Factory::createActionClass(ExtensionUninstallAction::class);

        $action->uninstallExtension(new ExtensionUninstallPayload('app', 'AppThatDoesNotExists'));
    }

    public function testTypeDoesNotExists(): void
    {
        static::expectNotToPerformAssertions();

        $action = Factory::createActionClass(ExtensionUninstallAction::class);

        $action->uninstallExtension(new ExtensionUninstallPayload('null', 'FooBar'));
    }

    public function testTypeIsEmpty(): void
    {
        $action = Factory::createActionClass(ExtensionUninstallAction::class);

        static::expectException(NotFoundException::class);

        $action->uninstallExtension(new ExtensionUninstallPayload('', 'FooBar'));
    }

    public function testExtensionNameIsEmpty(): void
    {
        $action = Factory::createActionClass(ExtensionUninstallAction::class);

        static::expectException(NotFoundException::class);

        $action->uninstallExtension(new ExtensionUninstallPayload('plugin', ''));
    }
}
