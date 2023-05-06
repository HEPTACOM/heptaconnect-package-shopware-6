<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch\SystemConfigBatchPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost\SystemConfigPostPayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigBatchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigPostAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnknownError;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigBatch\SystemConfigBatchPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigGet\SystemConfigGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\SystemConfigPost\SystemConfigPostPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigBatchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\SystemConfigPostAction
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\UnknownError
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
final class SystemConfigTest extends TestCase
{
    public function testReadWriteSingleAndBatchCycle(): void
    {
        $batchAction = Factory::createActionClass(SystemConfigBatchAction::class);
        $getAction = Factory::createActionClass(SystemConfigGetAction::class);
        $postAction = Factory::createActionClass(SystemConfigPostAction::class);

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
        $info = Factory::createActionClass(InfoVersionAction::class)->getVersion(new InfoVersionParams())->getVersion();

        // TODO we have to investigate what is happening here
        if (\version_compare($info, '6.4.1.0', '>')) {
            static::expectException(UnknownError::class);
        } else {
            static::expectNotToPerformAssertions();
        }

        $action = Factory::createActionClass(SystemConfigBatchAction::class);

        $action->batchSystemConfig(new SystemConfigBatchPayload([
            '00000000000000000000000000000000' => [
                'HeptaConnectPackageShopware6.config.testValue' => 'invalid',
            ],
        ]));
    }

    public function testWritingInvalidSalesChannelInPost(): void
    {
        $info = Factory::createActionClass(InfoVersionAction::class)->getVersion(new InfoVersionParams())->getVersion();

        // TODO we have to investigate what is happening here
        if (\version_compare($info, '6.4.1.0', '>')) {
            static::expectException(UnknownError::class);
        } else {
            static::expectNotToPerformAssertions();
        }

        $action = Factory::createActionClass(SystemConfigPostAction::class);

        $action->postSystemConfig(new SystemConfigPostPayload([
            'HeptaConnectPackageShopware6.config.testValue' => 'invalid',
        ], '00000000000000000000000000000000'));
    }

    public function testWritingEmptyValuesWithAnInvalidSalesChannelDoesNotFailInPost(): void
    {
        static::expectNotToPerformAssertions();

        $action = Factory::createActionClass(SystemConfigPostAction::class);

        $action->postSystemConfig(new SystemConfigPostPayload([], '00000000000000000000000000000000'));
    }

    public function testWritingUnkeyedValuesInPost(): void
    {
        $action = Factory::createActionClass(SystemConfigPostAction::class);

        static::expectException(UnknownError::class);

        $action->postSystemConfig(new SystemConfigPostPayload([123], '00000000000000000000000000000000'));
    }

    public function testReadDomainThatIsConfigurationKey(): void
    {
        $postAction = Factory::createActionClass(SystemConfigPostAction::class);

        $postAction->postSystemConfig(new SystemConfigPostPayload([
            'HeptaConnectPackageShopware6.config.testValue' => 'testReadDomainThatIsConfigurationKey',
        ]));

        $getAction = Factory::createActionClass(SystemConfigGetAction::class);

        static::assertSame([], $getAction->getSystemConfig(
            new SystemConfigGetCriteria('HeptaConnectPackageShopware6.config.testValue')
        )->getValues());
    }
}
