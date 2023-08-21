<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionActivate\ExtensionActivatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionDeactivate\ExtensionDeactivatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionInstall\ExtensionInstallPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRemove\ExtensionRemovePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch\SystemConfigBatchPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost\SystemConfigPostPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionActivateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionDeactivateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionInstallAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionRemoveAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUninstallAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUploadAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigBatchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigPostAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnknownError;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionActivate\ExtensionActivatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionDeactivate\ExtensionDeactivatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionInstall\ExtensionInstallPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRemove\ExtensionRemovePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch\SystemConfigBatchPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost\SystemConfigPostPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionActivateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionDeactivateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionInstallAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionRemoveAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUninstallAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUploadAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigBatchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigPostAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\DocumentNumberAlreadyExistsValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentFileGeneratorTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentIdValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\StateMachineInvalidEntityIdValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection\AdminApiFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnknownError
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\CartMissingOrderRelationValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidUuidValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MediaDuplicatedFileNameValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MediaFileTypeNotSupportedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ScopeNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\UnmappedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\WriteUnexpectedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\BaseFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Utility\DependencyInjection\SyntheticServiceContainer
 */
final class SystemConfigTest extends TestCase
{
    private static string $pluginName = '';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $factory = Factory::createAdminApiFactory();
        $streamFactory = $factory->getBaseFactory()->getStreamFactory();
        $upload = new ExtensionUploadAction($factory->getActionClientUtils(), $streamFactory);
        $install = new ExtensionInstallAction($factory->getActionClientUtils());
        $activate = new ExtensionActivateAction($factory->getActionClientUtils());

        $extensionName = self::createExtensionName();
        $upload->uploadExtension(new ExtensionUploadPayload(
            $extensionName . '.zip',
            $streamFactory->createStreamFromFile(self::createExtensionFile($extensionName))
        ));
        $install->installExtension(new ExtensionInstallPayload('plugin', $extensionName));
        $activate->activateExtension(new ExtensionActivatePayload('plugin', $extensionName));

        self::$pluginName = $extensionName;
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $factory = Factory::createAdminApiFactory();
        $deactivate = new ExtensionDeactivateAction($factory->getActionClientUtils());
        $uninstall = new ExtensionUninstallAction($factory->getActionClientUtils());
        $remove = new ExtensionRemoveAction($factory->getActionClientUtils());

        try {
            $deactivate->deactivateExtension(new ExtensionDeactivatePayload('plugin', self::$pluginName));
        } catch (\Throwable $_) {
        }

        try {
            $uninstall->uninstallExtension(new ExtensionUninstallPayload('plugin', self::$pluginName));
        } catch (\Throwable $_) {
        }

        try {
            $remove->removeExtension(new ExtensionRemovePayload('plugin', self::$pluginName));
        } catch (\Throwable $_) {
        }

        self::$pluginName = '';
    }

    public function testReadWriteSingleAndBatchCycle(): void
    {
        $actionClientUtils = Factory::createAdminApiFactory()->getActionClientUtils();
        $batchAction = new SystemConfigBatchAction($actionClientUtils);
        $getAction = new SystemConfigGetAction($actionClientUtils);
        $postAction = new SystemConfigPostAction($actionClientUtils);

        $coreSettings = $getAction->getSystemConfig(new SystemConfigGetCriteria('core'))->getValues();

        static::assertArrayHasKey('core.update.channel', $coreSettings);

        $batchAction->batchSystemConfig(new SystemConfigBatchPayload([
            SystemConfigBatchPayload::GLOBAL_SALES_CHANNEL => [
                self::$pluginName . '.config.testValue' => 'foobar',
            ],
        ]));

        $testSettings = $getAction->getSystemConfig(new SystemConfigGetCriteria(self::$pluginName . '.config'))->getValues();

        static::assertSame([
            self::$pluginName . '.config.testValue' => 'foobar',
        ], $testSettings);

        $postAction->postSystemConfig(new SystemConfigPostPayload([
            self::$pluginName . '.config.testValue' => 'foobaz',
        ]));

        $testSettings = $getAction->getSystemConfig(new SystemConfigGetCriteria(self::$pluginName . '.config'))->getValues();

        static::assertSame([
            self::$pluginName . '.config.testValue' => 'foobaz',
        ], $testSettings);
    }

    public function testWritingInvalidSalesChannelInBatch(): void
    {
        // TODO we have to investigate what is happening here
        if (\version_compare(Factory::getShopwareVersion(), '6.4.1.0', '>')) {
            static::expectException(UnknownError::class);
        } else {
            static::expectNotToPerformAssertions();
        }

        $action = new SystemConfigBatchAction(Factory::createAdminApiFactory()->getActionClientUtils());

        $action->batchSystemConfig(new SystemConfigBatchPayload([
            '00000000000000000000000000000000' => [
                self::$pluginName . '.config.testValue' => 'invalid',
            ],
        ]));
    }

    public function testWritingInvalidSalesChannelInPost(): void
    {
        // TODO we have to investigate what is happening here
        if (\version_compare(Factory::getShopwareVersion(), '6.4.1.0', '>')) {
            static::expectException(UnknownError::class);
        } else {
            static::expectNotToPerformAssertions();
        }

        $action = new SystemConfigPostAction(Factory::createAdminApiFactory()->getActionClientUtils());

        $action->postSystemConfig(new SystemConfigPostPayload([
            self::$pluginName . '.config.testValue' => 'invalid',
        ], '00000000000000000000000000000000'));
    }

    public function testWritingEmptyValuesWithAnInvalidSalesChannelDoesNotFailInPost(): void
    {
        static::expectNotToPerformAssertions();

        $action = new SystemConfigPostAction(Factory::createAdminApiFactory()->getActionClientUtils());

        $action->postSystemConfig(new SystemConfigPostPayload([], '00000000000000000000000000000000'));
    }

    public function testWritingUnkeyedValuesInPost(): void
    {
        $action = new SystemConfigPostAction(Factory::createAdminApiFactory()->getActionClientUtils());

        static::expectException(UnknownError::class);

        $action->postSystemConfig(new SystemConfigPostPayload([123], '00000000000000000000000000000000'));
    }

    public function testReadDomainThatIsConfigurationKey(): void
    {
        $postAction = new SystemConfigPostAction(Factory::createAdminApiFactory()->getActionClientUtils());

        $postAction->postSystemConfig(new SystemConfigPostPayload([
            self::$pluginName . '.config.testValue' => 'testReadDomainThatIsConfigurationKey',
        ]));

        $getAction = new SystemConfigGetAction(Factory::createAdminApiFactory()->getActionClientUtils());

        static::assertSame([], $getAction->getSystemConfig(
            new SystemConfigGetCriteria(self::$pluginName . '.config.testValue')
        )->getValues());
    }

    private static function createExtensionFile(string $name): string
    {
        $file = \sys_get_temp_dir() . '/' . $name . '.zip';

        $zipFile = new \ZipArchive();
        $zipFile->open($file, \ZipArchive::CREATE);
        $zipFile->addFromString($name . '/src/' . $name . '.php', '<?php namespace ' . $name . '; class ' . $name . ' extends \\Shopware\\Core\\Framework\\Plugin {}');
        $zipFile->addFromString(
            $name . '/src/Resources/config/config.xml',
            <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<config
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/platform/6.4.0.0/src/Core/System/SystemConfig/Schema/config.xsd"
>
    <card>
        <input-field>
            <name>testValue</name>
            <label>Test Value</label>
        </input-field>
    </card>
</config>
XML
        );
        $zipFile->addFromString($name . '/composer.json', \json_encode([
            'name' => 'heptaconnect-package/' . \mb_strtolower($name),
            'description' => $name,
            'version' => '1.0.0',
            'type' => 'shopware-platform-plugin',
            'license' => 'MIT',
            'authors' => [
                [
                    'name' => 'HEPTAconnect Package',
                    'role' => 'Manufacturer',
                ],
            ],
            'extra' => [
                'shopware-plugin-class' => $name . '\\' . $name,
                'label' => [
                    'de-DE' => $name,
                    'en-GB' => $name,
                ],
                'description' => [
                    'de-DE' => $name,
                    'en-GB' => $name,
                ],
                'manufacturerLink' => [
                    'de-DE' => 'http://' . $name . '.test',
                    'en-GB' => 'http://' . $name . '.test',
                ],
                'supportLink' => [
                    'de-DE' => 'http://' . $name . '.test',
                    'en-GB' => 'http://' . $name . '.test',
                ],
            ],
            'autoload' => [
                'psr-4' => [
                    $name . '\\' => 'src',
                ],
            ],
        ]));

        $zipFile->close();

        return $file;
    }

    private static function createExtensionName(): string
    {
        return 'HeptaConnectExt' . \bin2hex(\random_bytes(8));
    }
}
