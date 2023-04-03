<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperation;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationCollection;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationResult;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception\SyncResultException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SyncAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\JsonResponseValidationCollectionException;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncOperationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Sync\SyncResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Exception\SyncResultException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SyncAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\JsonResponseValidationCollectionException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\UnknownError
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class SyncActionTest extends AbstractActionTestCase
{
    public function testUpsertAndDeleteTag(): void
    {
        $action = $this->createAction(SyncAction::class);

        $result = $action->sync((new SyncPayload())->withSyncOperations(new SyncOperationCollection([
            (new SyncOperation('tag', SyncOperation::ACTION_UPSERT, 'tag-upsert'))->withAddedPayload([
                'name' => 'random-tag-' . \bin2hex(\random_bytes(8)),
            ]),
        ])));

        static::assertTrue($result->getOperationResults()->hasKey('tag-upsert'));

        $operationResult = $result->getOperationResults()->getKey('tag-upsert');

        static::assertInstanceOf(SyncOperationResult::class, $operationResult);
        static::assertSame([], $operationResult->getErrors());

        $tagId = $operationResult->getEntities()[0]['tag'][0] ?? null;

        static::assertIsString($tagId);

        $result = $action->sync((new SyncPayload())->withSyncOperations(new SyncOperationCollection([
            (new SyncOperation('tag', SyncOperation::ACTION_DELETE, 'tag-delete'))->withAddedPayload([
                'id' => $tagId,
            ]),
        ])));

        static::assertTrue($result->getOperationResults()->hasKey('tag-delete'));

        $operationResult = $result->getOperationResults()->getKey('tag-delete');

        static::assertInstanceOf(SyncOperationResult::class, $operationResult);
        static::assertSame([], $operationResult->getErrors());
    }

    public function testFailOnWronglyShapedEntitiesData(): void
    {
        $action = $this->createAction(SyncAction::class);

        static::expectException(SyncResultException::class);

        $action->sync((new SyncPayload())->withSyncOperations(new SyncOperationCollection([
            (new SyncOperation('tag', SyncOperation::ACTION_UPSERT, 'tag-upsert'))->withAddedPayload([
                'random-tag-' . \bin2hex(\random_bytes(8)),
            ]),
        ])));
    }

    public function testGroupExceptionsOnMultipleWronglyShapedEntitiesData(): void
    {
        $action = $this->createAction(SyncAction::class);

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
        $action = $this->createAction(SyncAction::class);
        $result = $action->sync((new SyncPayload())->withSyncOperations(new SyncOperationCollection([
            (new SyncOperation('tag', SyncOperation::ACTION_DELETE, 'tag-delete'))->withAddedPayload([
                'id' => '00000000000000000000000000000000',
            ]),
        ])));

        static::assertTrue($result->getOperationResults()->hasKey('tag-delete'));

        $operationResult = $result->getOperationResults()->getKey('tag-delete');

        static::assertInstanceOf(SyncOperationResult::class, $operationResult);
        static::assertSame([], $operationResult->getErrors());
        static::assertSame([[]], $operationResult->getEntities());
    }
}