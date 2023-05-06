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
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchIdAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotActivatedException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotInstalledException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\ExtensionClient;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationBucket
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationBucketCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\AbstractFieldAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\AbstractFieldFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\AbstractNestedFilters
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\NotFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\AbstractExtensionPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRefresh\ExtensionRefreshParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpdate\ExtensionUpdatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadPayload
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchIdAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotActivatedException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotFoundException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\PluginNotInstalledException
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\ExtensionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\ResourceNotFoundException
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\LetterCase
 */
final class ExtensionClientTest extends TestCase
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
            Factory::createActionClass(ExtensionRefreshAction::class),
            Factory::createActionClass(ExtensionActivateAction::class),
            Factory::createActionClass(ExtensionDeactivateAction::class),
            Factory::createActionClass(ExtensionInstallAction::class),
            Factory::createActionClass(ExtensionUninstallAction::class),
            Factory::createActionClass(ExtensionUpdateAction::class),
            Factory::createActionClass(ExtensionUploadAction::class, BaseFactory::createStreamFactory()),
            Factory::createActionClass(ExtensionRemoveAction::class),
            Factory::createActionClass(EntitySearchAction::class, new CriteriaFormatter()),
            Factory::createActionClass(EntitySearchIdAction::class, new CriteriaFormatter()),
            Factory::createActionClass(StorePluginSearchAction::class),
            BaseFactory::createStreamFactory(),
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
