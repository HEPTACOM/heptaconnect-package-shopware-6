<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\MediaUploadActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\MediaUploadByStreamPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\MediaUploadByUrlPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\MediaUploadAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchIdAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaDuplicatedFileNameException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaFileTypeNotSupportedException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\AbstractFieldFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\AbstractMediaUploadPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\MediaUploadByStreamPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\MediaUpload\MediaUploadByUrlPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\MediaUploadAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchIdAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\DocumentNumberAlreadyExistsException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\DocumentNumberAlreadyExistsValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionInstallValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExtensionNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidDocumentFileGeneratorTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNoPluginFoundInZipValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotActivatedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\StateMachineInvalidEntityIdValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\DependencyInjection\AdminApiFactory
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Contract\JsonResponseValidatorCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaDuplicatedFileNameException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\MediaFileTypeNotSupportedException
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
final class MediaUploadActionTest extends TestCase
{
    private static MediaUploadActionInterface $action;

    private static EntityCreateActionInterface $create;

    private static EntitySearchActionInterface $search;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $factory = Factory::createAdminApiFactory();
        $actionClientUtils = $factory->getActionClientUtils();
        self::$action = new MediaUploadAction($actionClientUtils);
        self::$create = new EntityCreateAction($actionClientUtils);
        self::$search = new EntitySearchAction($actionClientUtils, new CriteriaFormatter());
    }

    public function testUploadMediaByUrl(): void
    {
        $mediaId = self::$create->create(new EntityCreatePayload('media', $this->createMediaPayload()))->getId();
        $media = $this->getMedias($mediaId)[$mediaId];

        static::assertNull($media['mimeType']);
        static::assertNull($media['fileExtension']);
        static::assertNull($media['fileName']);
        static::assertNull($media['mediaType']);
        static::assertNull($media['metaData']);
        static::assertFalse($media['hasFile']);

        self::$action->uploadMedia(new MediaUploadByUrlPayload('http://via.placeholder.com/100x100', $mediaId, 'png'));
        $media = $this->getMedias($mediaId)[$mediaId];

        static::assertSame($media['mimeType'], 'image/png');
        static::assertSame($media['fileExtension'], 'png');
        static::assertSame($media['fileName'], $media['id']);
        static::assertSame($media['mediaType']['name'], 'IMAGE');
        static::assertSame($media['metaData']['width'], 100);
        static::assertSame($media['metaData']['height'], 100);
        static::assertTrue($media['hasFile']);
    }

    public function testUploadMediaSucceedsByUrlThatDoesNotProvideImageButExpectsOne(): void
    {
        $mediaId = self::$create->create(new EntityCreatePayload('media', $this->createMediaPayload()))->getId();
        $media = $this->getMedias($mediaId)[$mediaId];

        static::assertNull($media['mimeType']);
        static::assertNull($media['fileExtension']);
        static::assertNull($media['fileName']);
        static::assertNull($media['mediaType']);
        static::assertNull($media['metaData']);
        static::assertFalse($media['hasFile']);

        self::$action->uploadMedia(new MediaUploadByUrlPayload('https://placeholder.com/', $mediaId, 'png'));

        $media = $this->getMedias($mediaId)[$mediaId];

        static::assertSame($media['mimeType'], 'text/html');
        static::assertSame($media['fileExtension'], 'png');
        static::assertSame($media['fileName'], $media['id']);
        static::assertSame($media['mediaType']['name'], 'IMAGE');
        static::assertTrue($media['hasFile']);
    }

    public function testUploadMediaByUrlThatProvidesInvalidMediaContent(): void
    {
        $mediaId = self::$create->create(new EntityCreatePayload('media', $this->createMediaPayload()))->getId();
        $initMedia = $this->getMedias($mediaId)[$mediaId];

        try {
            self::$action->uploadMedia(new MediaUploadByUrlPayload('https://placeholder.com/', $mediaId, 'html'));
            static::fail('Wrong exception');
        } catch (MediaFileTypeNotSupportedException $exception) {
        }

        $media = $this->getMedias($mediaId)[$mediaId];

        static::assertSame($initMedia, $media);
    }

    public function testUploadMediaByStream(): void
    {
        $streamFactory = Factory::createAdminApiFactory()->getBaseFactory()->getStreamFactory();
        $mediaId = self::$create->create(new EntityCreatePayload('media', $this->createMediaPayload()))->getId();
        $media = $this->getMedias($mediaId)[$mediaId];

        static::assertNull($media['mimeType']);
        static::assertNull($media['fileExtension']);
        static::assertNull($media['fileName']);
        static::assertNull($media['mediaType']);
        static::assertNull($media['metaData']);
        static::assertFalse($media['hasFile']);

        self::$action->uploadMedia(new MediaUploadByStreamPayload($streamFactory->createStream($this->createJpg()), $mediaId, 'jpg'));

        $media = $this->getMedias($mediaId)[$mediaId];

        static::assertSame($media['mimeType'], 'image/jpeg');
        static::assertSame($media['fileExtension'], 'jpg');
        static::assertSame($media['fileName'], $media['id']);
        static::assertSame($media['mediaType']['name'], 'IMAGE');
        static::assertSame($media['metaData']['width'], 10);
        static::assertSame($media['metaData']['height'], 10);
        static::assertTrue($media['hasFile']);
    }

    public function testUploadMediaByUrlTwiceToTheSameMedia(): void
    {
        $mediaId = self::$create->create(new EntityCreatePayload('media', $this->createMediaPayload()))->getId();
        self::$action->uploadMedia(new MediaUploadByUrlPayload('http://via.placeholder.com/100x100', $mediaId, 'png'));
        $media1 = $this->getMedias($mediaId)[$mediaId];
        self::$action->uploadMedia(new MediaUploadByUrlPayload('http://via.placeholder.com/200x200', $mediaId, 'png'));
        $media2 = $this->getMedias($mediaId)[$mediaId];

        static::assertNotSame($media1, $media2);

        unset(
            $media1['updatedAt'], $media2['updatedAt'],
            $media1['uploadedAt'], $media2['uploadedAt'],
            $media1['url'], $media2['url'],
            $media1['fileSize'], $media2['fileSize'],
            $media1['metaData'], $media2['metaData'],
            $media1['thumbnails'], $media2['thumbnails'],
        );

        static::assertSame($media1, $media2);
    }

    public function testUploadMediaByUrlTwiceToTheSameMediaWithTheSameFilename(): void
    {
        $mediaId = self::$create->create(new EntityCreatePayload('media', $this->createMediaPayload()))->getId();
        $filename = 'my-media-id-is-the-filename-' . $mediaId;
        self::$action->uploadMedia(new MediaUploadByUrlPayload('http://via.placeholder.com/100x100', $mediaId, 'png', $filename));
        $media1 = $this->getMedias($mediaId)[$mediaId];
        self::$action->uploadMedia(new MediaUploadByUrlPayload('http://via.placeholder.com/100x100', $mediaId, 'png', $filename));
        $media2 = $this->getMedias($mediaId)[$mediaId];

        static::assertNotSame($media1, $media2);

        unset(
            $media1['updatedAt'], $media2['updatedAt'],
            $media1['uploadedAt'], $media2['uploadedAt'],
            $media1['url'], $media2['url'],
            $media1['fileSize'], $media2['fileSize'],
            $media1['metaData'], $media2['metaData'],
            $media1['thumbnails'], $media2['thumbnails'],
        );

        static::assertSame($media1, $media2);
    }

    public function testUploadMediaByUrlTwiceToDifferentMediaWithTheSameFilename(): void
    {
        $mediaId1 = self::$create->create(new EntityCreatePayload('media', $this->createMediaPayload()))->getId();
        $mediaId2 = self::$create->create(new EntityCreatePayload('media', $this->createMediaPayload()))->getId();
        $filename = 'my-media-id-is-the-filename-' . $mediaId1;
        self::$action->uploadMedia(new MediaUploadByUrlPayload('http://via.placeholder.com/100x100', $mediaId1, 'png', $filename));

        $media = $this->getMedias($mediaId1)[$mediaId1];

        static::assertSame($filename, $media['fileName']);
        static::expectException(MediaDuplicatedFileNameException::class);

        self::$action->uploadMedia(new MediaUploadByUrlPayload('http://via.placeholder.com/100x100', $mediaId2, 'png', $filename));
    }

    private function createJpg(): string
    {
        $image = \imagecreate(10, 10);
        \imagefill($image, 0, 0, \imagecolorallocate($image, 255, 255, 0));
        \ob_start();
        \imagejpeg($image);

        return \ob_get_clean();
    }

    private function createMediaPayload(): array
    {
        $entitySearchId = new EntitySearchIdAction(Factory::createAdminApiFactory()->getActionClientUtils(), new CriteriaFormatter());
        $mediaFolderCriteria = (new Criteria())
            ->withLimit(1)
            ->withAndFilter(new EqualsFilter('defaultFolder.entity', 'product'));
        $mediaFolderId = $entitySearchId->searchIds(new EntitySearchIdCriteria('media-folder', $mediaFolderCriteria))->getData()[0];

        return [
            'private' => false,
            'mediaFolderId' => $mediaFolderId,
        ];
    }

    private function getMedias(string ...$mediaId): array
    {
        $result = self::$search->search(new EntitySearchCriteria('media', (new Criteria())->withIds([...$mediaId])))
            ->getData()
            ->asArray();

        return \array_column($result, null, 'id');
    }
}
