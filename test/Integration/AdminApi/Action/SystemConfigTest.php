<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch\SystemConfigBatchPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost\SystemConfigPostPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigBatchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigPostAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\UnknownError;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch\SystemConfigBatchPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost\SystemConfigPostPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigBatchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigPostAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\UnknownError
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ExpectationFailedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class SystemConfigTest extends AbstractActionTestCase
{
    public function testReadWriteSingleAndBatchCycle(): void
    {
        $batchAction = $this->createAction(SystemConfigBatchAction::class);
        $getAction = $this->createAction(SystemConfigGetAction::class);
        $postAction = $this->createAction(SystemConfigPostAction::class);

        $coreSettings = $getAction->getSystemConfig(new SystemConfigGetCriteria('core'))->getValues();

        static::assertArrayHasKey('core.update.channel', $coreSettings);

        $batchAction->batchSystemConfig(new SystemConfigBatchPayload([
            SystemConfigBatchPayload::GLOBAL_SALES_CHANNEL => [
                'HeptaConnectPackageShopware6.config.testValue' => 'foobar',
            ],
        ]));

        $testSettings = $getAction->getSystemConfig(new SystemConfigGetCriteria('HeptaConnectPackageShopware6.config'))->getValues();

        static::assertSame([
            'HeptaConnectPackageShopware6.config.testValue' => 'foobar',
        ], $testSettings);

        $postAction->postSystemConfig(new SystemConfigPostPayload([
            'HeptaConnectPackageShopware6.config.testValue' => 'foobaz',
        ]));

        $testSettings = $getAction->getSystemConfig(new SystemConfigGetCriteria('HeptaConnectPackageShopware6.config'))->getValues();

        static::assertSame([
            'HeptaConnectPackageShopware6.config.testValue' => 'foobaz',
        ], $testSettings);
    }

    public function testWritingInvalidSalesChannelInBatch(): void
    {
        $info = $this->createAction(InfoAction::class)->getInfo(new InfoParams())->getVersion();

        // TODO we have to investigate what is happening here
        if (\version_compare($info, '6.4.1.0', '>')) {
            static::expectException(UnknownError::class);
        } else {
            static::expectNotToPerformAssertions();
        }

        $action = $this->createAction(SystemConfigBatchAction::class);

        $action->batchSystemConfig(new SystemConfigBatchPayload([
            '00000000000000000000000000000000' => [
                'HeptaConnectPackageShopware6.config.testValue' => 'invalid',
            ],
        ]));
    }

    public function testWritingInvalidSalesChannelInPost(): void
    {
        $info = $this->createAction(InfoAction::class)->getInfo(new InfoParams())->getVersion();

        // TODO we have to investigate what is happening here
        if (\version_compare($info, '6.4.1.0', '>')) {
            static::expectException(UnknownError::class);
        } else {
            static::expectNotToPerformAssertions();
        }

        $action = $this->createAction(SystemConfigPostAction::class);

        $action->postSystemConfig(new SystemConfigPostPayload([
            'HeptaConnectPackageShopware6.config.testValue' => 'invalid',
        ], '00000000000000000000000000000000'));
    }

    public function testWritingEmptyValuesWithAnInvalidSalesChannelDoesNotFailInPost(): void
    {
        static::expectNotToPerformAssertions();

        $action = $this->createAction(SystemConfigPostAction::class);

        $action->postSystemConfig(new SystemConfigPostPayload([], '00000000000000000000000000000000'));
    }

    public function testWritingUnkeyedValuesInPost(): void
    {
        $action = $this->createAction(SystemConfigPostAction::class);

        static::expectException(UnknownError::class);

        $action->postSystemConfig(new SystemConfigPostPayload([123], '00000000000000000000000000000000'));
    }

    public function testReadDomainThatIsConfigurationKey(): void
    {
        $postAction = $this->createAction(SystemConfigPostAction::class);

        $postAction->postSystemConfig(new SystemConfigPostPayload([
            'HeptaConnectPackageShopware6.config.testValue' => 'testReadDomainThatIsConfigurationKey',
        ]));

        $getAction = $this->createAction(SystemConfigGetAction::class);

        static::assertSame([], $getAction->getSystemConfig(
            new SystemConfigGetCriteria('HeptaConnectPackageShopware6.config.testValue')
        )->getValues());
    }
}
