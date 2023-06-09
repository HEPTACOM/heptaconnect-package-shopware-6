<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\NotFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionActivate\ExtensionActivateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionActivate\ExtensionActivatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionDeactivate\ExtensionDeactivateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionDeactivate\ExtensionDeactivatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionInstall\ExtensionInstallActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionInstall\ExtensionInstallPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRefresh\ExtensionRefreshActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRefresh\ExtensionRefreshParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRemove\ExtensionRemoveActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionRemove\ExtensionRemovePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUninstall\ExtensionUninstallPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpdate\ExtensionUpdateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpdate\ExtensionUpdatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\ExtensionUpload\ExtensionUploadPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginSearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\StorePluginSearch\StorePluginSearchParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdCriteria;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Facade to extension action methods with a reduced set of arguments.
 * If you want to intercept a process, use the action services.
 */
final class ExtensionClient
{
    private array $extensionTypeCache = [];

    private ExtensionRefreshActionInterface $refreshAction;

    private ExtensionActivateActionInterface $activateAction;

    private ExtensionDeactivateActionInterface $deactivateAction;

    private ExtensionInstallActionInterface $installAction;

    private ExtensionUninstallActionInterface $uninstallAction;

    private ExtensionUpdateActionInterface $updateAction;

    private ExtensionUploadActionInterface $uploadAction;

    private ExtensionRemoveActionInterface $removeAction;

    private EntitySearchActionInterface $entitySearchAction;

    private StorePluginSearchActionInterface $pluginSearchAction;

    private EntitySearchIdActionInterface $entitySearchIdAction;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        ExtensionRefreshActionInterface $refreshAction,
        ExtensionActivateActionInterface $activateAction,
        ExtensionDeactivateActionInterface $deactivateAction,
        ExtensionInstallActionInterface $installAction,
        ExtensionUninstallActionInterface $uninstallAction,
        ExtensionUpdateActionInterface $updateAction,
        ExtensionUploadActionInterface $uploadAction,
        ExtensionRemoveActionInterface $removeAction,
        EntitySearchActionInterface $entitySearchAction,
        EntitySearchIdActionInterface $entitySearchIdAction,
        StorePluginSearchActionInterface $pluginSearchAction,
        StreamFactoryInterface $streamFactory
    ) {
        $this->refreshAction = $refreshAction;
        $this->activateAction = $activateAction;
        $this->deactivateAction = $deactivateAction;
        $this->installAction = $installAction;
        $this->uninstallAction = $uninstallAction;
        $this->updateAction = $updateAction;
        $this->uploadAction = $uploadAction;
        $this->removeAction = $removeAction;
        $this->entitySearchAction = $entitySearchAction;
        $this->entitySearchIdAction = $entitySearchIdAction;
        $this->pluginSearchAction = $pluginSearchAction;
        $this->streamFactory = $streamFactory;
    }

    /**
     * Refresh the data in the extension listing.
     *
     * @throws \Throwable
     */
    public function refresh(): void
    {
        $this->refreshAction->refreshExtensions(new ExtensionRefreshParams());
    }

    /**
     * Activates the referenced extension.
     *
     * @throws \Throwable
     */
    public function activate(string $extensionName): void
    {
        $this->activateAction->activateExtension(new ExtensionActivatePayload($this->getExtensionType($extensionName), $extensionName));
    }

    /**
     * Deactivates the referenced extension.
     *
     * @throws \Throwable
     */
    public function deactivate(string $extensionName): void
    {
        $this->deactivateAction->deactivateExtension(new ExtensionDeactivatePayload($this->getExtensionType($extensionName), $extensionName));
    }

    /**
     * Check the activeness state of the referenced extension.
     */
    public function isActive(string $extensionName): bool
    {
        $criteria = (new Criteria())
            ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NEXT_PAGES)
            ->withAndFilter(new EqualsFilter('name', $extensionName))
            ->withAndFilter(new EqualsFilter('active', true));
        $result = $this->entitySearchIdAction->searchIds(new EntitySearchIdCriteria('plugin', $criteria));

        return $result->getTotal() > 0;
    }

    /**
     * Installs the referenced extension.
     *
     * @throws \Throwable
     */
    public function install(string $extensionName): void
    {
        $this->installAction->installExtension(new ExtensionInstallPayload($this->getExtensionType($extensionName), $extensionName));
    }

    /**
     * Uninstalls the referenced extension.
     *
     * @throws \Throwable
     */
    public function uninstall(string $extensionName): void
    {
        $this->uninstallAction->uninstallExtension(new ExtensionUninstallPayload($this->getExtensionType($extensionName), $extensionName));
    }

    /**
     * Check the installation state of the referenced extension.
     */
    public function isInstalled(string $extensionName): bool
    {
        $criteria = (new Criteria())
            ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NEXT_PAGES)
            ->withAndFilter(new EqualsFilter('name', $extensionName))
            ->withAndFilter(new NotFilter(new FilterCollection([
                new EqualsFilter('installedAt', null),
            ])));
        $result = $this->entitySearchIdAction->searchIds(new EntitySearchIdCriteria('plugin', $criteria));

        return $result->getTotal() > 0;
    }

    /**
     * Performs update steps of the referenced extension.
     *
     * @throws \Throwable
     */
    public function update(string $extensionName): void
    {
        $this->updateAction->updateExtension(new ExtensionUpdatePayload($this->getExtensionType($extensionName), $extensionName));
    }

    /**
     * Upload the local file as ZIP file.
     *
     * @throws \Throwable
     */
    public function upload(string $file): void
    {
        $filename = \basename($file);
        $stream = $this->streamFactory->createStreamFromFile($file);

        $this->uploadAction->uploadExtension(new ExtensionUploadPayload($filename, $stream));
    }

    /**
     * Removes the referenced extension.
     *
     * @throws \Throwable
     */
    public function remove(string $extensionName): void
    {
        $this->removeAction->removeExtension(new ExtensionRemovePayload($this->getExtensionType($extensionName), $extensionName));
    }

    /**
     * Check the referenced extension exists in the shop.
     */
    public function exists(string $extensionName): bool
    {
        $criteria = (new Criteria())
            ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NEXT_PAGES)
            ->withAndFilter(new EqualsFilter('name', $extensionName));
        $result = $this->entitySearchIdAction->searchIds(new EntitySearchIdCriteria('plugin', $criteria));

        return $result->getTotal() > 0;
    }

    /**
     * Lists the available extensions in the shop.
     *
     * @throws \Throwable
     */
    public function listExtensions(): StringCollection
    {
        $criteria = (new Criteria())->withAddedAggregation(new TermsAggregation('name', 'name'));
        $nameBucket = $this->entitySearchAction->search(new EntitySearchCriteria('plugin', $criteria))->getAggregations()['name'];

        return $nameBucket->buckets->getKeys();
    }

    private function getExtensionType(string $extensionName): string
    {
        $result = $this->extensionTypeCache[$extensionName] ?? null;

        if ($result === null) {
            $this->fetchExtensionTypes();
            $result = $this->extensionTypeCache[$extensionName] ?? 'null';
        }

        return $result;
    }

    private function fetchExtensionTypes(): void
    {
        $plugins = $this->pluginSearchAction->searchPluginStore(new StorePluginSearchParams())->getItems();
        $types = [];

        foreach ($plugins as $plugin) {
            $types[$plugin->name] = $plugin->type;
        }

        $this->extensionTypeCache = $types;
    }
}
