<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperation;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception\SyncResultException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SyncAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction\SyncPayloadInterceptorCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction\SyncPayloadInterceptorInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\FieldIsBlankException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\JsonResponseValidationCollectionException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception\SyncResultException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SyncAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticationMemoryCache
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Contract\SyncAction\SyncPayloadInterceptorCollection
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\FieldIsBlankException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\JsonResponseValidationCollectionException
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
final class SyncActionTest extends TestCase
{
    public function testUpsertAndDeleteTag(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new SyncAction($factory->getActionClientUtils(), new SyncPayloadInterceptorCollection(), $factory->getBaseFactory()->getJsonStreamUtility());

        $result = $action->sync((new SyncPayload())->withSyncOperation('tag', SyncOperation::ACTION_UPSERT, [
            'name' => 'random-tag-' . \bin2hex(\random_bytes(8)),
        ], 'tag-upsert'));

        static::assertTrue($result->getOperationResults()->hasKey('tag-upsert'));

        $operationResult = $result->getOperationResults()->getKey('tag-upsert');

        static::assertInstanceOf(SyncOperationResult::class, $operationResult);
        static::assertSame([], $operationResult->getErrors());

        $tagId = $operationResult->getEntities()[0]['tag'][0] ?? null;

        static::assertIsString($tagId);

        $result = $action->sync((new SyncPayload())->withSyncOperation('tag', SyncOperation::ACTION_DELETE, [
            'id' => $tagId,
        ], 'tag-delete'));

        static::assertTrue($result->getOperationResults()->hasKey('tag-delete'));

        $operationResult = $result->getOperationResults()->getKey('tag-delete');

        static::assertInstanceOf(SyncOperationResult::class, $operationResult);
        static::assertSame([], $operationResult->getErrors());
    }

    public function testFailOnWronglyShapedEntitiesData(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new SyncAction($factory->getActionClientUtils(), new SyncPayloadInterceptorCollection(), $factory->getBaseFactory()->getJsonStreamUtility());

        static::expectException(SyncResultException::class);

        $action->sync((new SyncPayload())->withSyncOperation('tag', SyncOperation::ACTION_UPSERT, [
            'random-tag-' . \bin2hex(\random_bytes(8)),
        ]));
    }

    public function testGroupExceptionsOnMultipleWronglyShapedEntitiesData(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new SyncAction($factory->getActionClientUtils(), new SyncPayloadInterceptorCollection(), $factory->getBaseFactory()->getJsonStreamUtility());

        try {
            $action->sync((new SyncPayload())->withSyncOperations(new SyncOperationCollection([
                (new SyncOperation('tag', SyncOperation::ACTION_UPSERT, 'tag-upsert'))->withAddedPayload([
                    'random-tag-' . \bin2hex(\random_bytes(8)),
                ])->withAddedPayload([
                    'random-tag-' . \bin2hex(\random_bytes(8)),
                ]),
            ])));
        } catch (SyncResultException $syncResultException) {
            $previous = $syncResultException->getPrevious();

            static::assertInstanceOf(JsonResponseValidationCollectionException::class, $previous);
            static::assertCount(2, $previous->getExceptions());
        }
    }

    public function testDeleteNonExistingTags(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new SyncAction($factory->getActionClientUtils(), new SyncPayloadInterceptorCollection(), $factory->getBaseFactory()->getJsonStreamUtility());
        $result = $action->sync((new SyncPayload())->withSyncOperation('tag', SyncOperation::ACTION_DELETE, [
            'id' => '00000000000000000000000000000000',
        ], 'tag-delete'));

        static::assertTrue($result->getOperationResults()->hasKey('tag-delete'));

        $operationResult = $result->getOperationResults()->getKey('tag-delete');

        static::assertInstanceOf(SyncOperationResult::class, $operationResult);
        static::assertSame([], $operationResult->getErrors());
        static::assertSame([[]], $operationResult->getEntities());
    }

    public function testFailOnEmptyTags(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new SyncAction($factory->getActionClientUtils(), new SyncPayloadInterceptorCollection(), $factory->getBaseFactory()->getJsonStreamUtility());

        try {
            $action->sync((new SyncPayload())->withSyncOperations(new SyncOperationCollection([
                (new SyncOperation('tag', SyncOperation::ACTION_UPSERT, 'tags-upsert'))->withAddedPayload([
                    'name' => '',
                ])->withAddedPayload([
                    'name' => '',
                ]),
            ])));
        } catch (SyncResultException $syncResultException) {
            static::assertTrue($syncResultException->getSyncResult()->getOperationResults()->hasKey('tags-upsert'));

            $operationResult = $syncResultException->getSyncResult()->getOperationResults()->getKey('tags-upsert');

            static::assertInstanceOf(SyncOperationResult::class, $operationResult);
            static::assertNotSame([], $operationResult->getEntities());

            $previous = $syncResultException->getPrevious();

            static::assertInstanceOf(JsonResponseValidationCollectionException::class, $previous);
            static::assertCount(2, $previous->getExceptions());

            foreach ($previous->getExceptions() as $innerException) {
                static::assertInstanceOf(FieldIsBlankException::class, $innerException);
            }
        }
    }

    public function testSyncPayloadInterceptor(): void
    {
        $factory = Factory::createAdminApiFactory();
        $action = new SyncAction($factory->getActionClientUtils(), new SyncPayloadInterceptorCollection([
            new class() implements SyncPayloadInterceptorInterface {
                public function intercept(SyncPayload $payload): SyncPayload
                {
                    return $payload->withSyncOperations(new SyncOperationCollection());
                }
            },
        ]), $factory->getBaseFactory()->getJsonStreamUtility());

        // this would fail
        $result = $action->sync((new SyncPayload())->withSyncOperation('tag', SyncOperation::ACTION_UPSERT, [
            'name' => '',
        ]));

        static::assertTrue($result->getOperationResults()->isEmpty());
    }
}
