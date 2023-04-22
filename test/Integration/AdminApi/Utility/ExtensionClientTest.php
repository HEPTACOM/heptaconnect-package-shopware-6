<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Utility;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionActivateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionDeactivateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionInstallAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionRefreshAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionRemoveAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUninstallAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUpdateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUploadAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\StorePluginSearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotActivatedException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotInstalledException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\ExtensionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action\AbstractActionTestCase;
use Http\Discovery\Psr17FactoryDiscovery;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\AbstractExtensionPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRefresh\ExtensionRefreshParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpdate\ExtensionUpdatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePlugin
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginSearchParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginSearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionActivateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionDeactivateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionInstallAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionRefreshAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionRemoveAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUninstallAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUpdateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\ExtensionUploadAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\StorePluginSearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotActivatedException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotInstalledException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ResourceNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\LetterCase
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\ExtensionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class ExtensionClientTest extends AbstractActionTestCase
{
    public function testPluginLifecycle(): void
    {
        $client = $this->createEntityClient();
        $client->refresh();
        $newExtension = $this->createExtensionName();

        static::assertFalse($client->exists($newExtension));

        $client->upload($this->createExtensionFile($newExtension, '1.0.0'));

        static::assertTrue($client->exists($newExtension));

        $newExtensions = $client->listExtensions();

        static::assertContains($newExtension, $newExtensions);
        static::assertIsString($newExtension);

        static::assertFalse($client->isInstalled($newExtension));

        $client->install($newExtension);

        static::assertTrue($client->isInstalled($newExtension));

        $client->install($newExtension);

        static::assertFalse($client->isActive($newExtension));

        $client->activate($newExtension);

        static::assertTrue($client->isActive($newExtension));

        $client->activate($newExtension);
        $client->upload($this->createExtensionFile($newExtension, '1.1.0'));
        $client->refresh();
        $client->update($newExtension);

        static::assertTrue($client->isActive($newExtension));

        $client->deactivate($newExtension);

        static::assertFalse($client->isActive($newExtension));

        try {
            $client->deactivate($newExtension);
        } catch (PluginNotActivatedException $exception) {
            static::assertSame($newExtension, $exception->getPluginName());
        }

        static::assertTrue($client->isInstalled($newExtension));

        $client->uninstall($newExtension);

        static::assertFalse($client->isInstalled($newExtension));

        try {
            $client->uninstall($newExtension);
        } catch (PluginNotInstalledException $exception) {
            static::assertSame($newExtension, $exception->getPluginName());
        }

        static::assertTrue($client->exists($newExtension));

        $client->remove($newExtension);

        static::assertFalse($client->exists($newExtension));

        try {
            $client->remove($newExtension);
        } catch (PluginNotFoundException $exception) {
            static::assertSame($newExtension, $exception->getPluginName());
        }
    }

    private function createEntityClient(): ExtensionClient
    {
        return new ExtensionClient(
            $this->createAction(ExtensionRefreshAction::class),
            $this->createAction(ExtensionActivateAction::class),
            $this->createAction(ExtensionDeactivateAction::class),
            $this->createAction(ExtensionInstallAction::class),
            $this->createAction(ExtensionUninstallAction::class),
            $this->createAction(ExtensionUpdateAction::class),
            $this->createAction(ExtensionUploadAction::class, Psr17FactoryDiscovery::findStreamFactory()),
            $this->createAction(ExtensionRemoveAction::class),
            $this->createAction(EntitySearchAction::class, new CriteriaFormatter()),
            $this->createAction(StorePluginSearchAction::class),
            Psr17FactoryDiscovery::findStreamFactory(),
        );
    }

    private function createExtensionFile(string $name, string $version): string
    {
        $file = \sys_get_temp_dir() . '/' . $name . '.zip';

        $zipFile = new \ZipArchive();
        $zipFile->open($file, \ZipArchive::CREATE);
        $zipFile->addFromString($name . '/src/' . $name . '.php', '<?php namespace ' . $name . '; class ' . $name . ' extends \\Shopware\\Core\\Framework\\Plugin {}');
        $zipFile->addFromString($name . '/composer.json', \json_encode([
            'name' => 'heptaconnect-package/' . \mb_strtolower($name),
            'description' => $name,
            'version' => $version,
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

    private function createExtensionName(): string
    {
        return 'HeptaConnectExt' . \bin2hex(\random_bytes(8));
    }
}
