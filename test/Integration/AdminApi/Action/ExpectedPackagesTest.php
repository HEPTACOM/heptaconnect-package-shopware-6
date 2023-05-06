<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExpectationFailedException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\InfoVersion\InfoVersionResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoVersionAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
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
final class ExpectedPackagesTest extends TestCase
{
    public function testVersionCanBeMatchedWithConstraints(): void
    {
        $action = Factory::createActionClass(InfoVersionAction::class);
        $version = $action->getVersion(new InfoVersionParams())->getVersion();
        $params = new InfoVersionParams();

        $params = $params->withExpectedPackage('shopware/core', $version);

        $action->getVersion($params);
        $action->getVersion($params->withAddedExpectedPackage('shopware/core', '>=' . $version));
        $action->getVersion($params->withAddedExpectedPackage('shopware/core', '<=' . $version));

        $params = $params->withoutExpectedPackage('shopware/core');
        $params = $params->withExpectedPackage('shopware/core', '>' . $version);

        static::expectException(ExpectationFailedException::class);

        $action->getVersion($params);
    }

    public function testExpectedPackagesInputIsValidatedWhenPackageIsEmpty(): void
    {
        $params = new InfoVersionParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447700);

        $params->withoutExpectedPackage('');
    }

    public function testExpectedPackagesInputIsValidatedWhenPackageVendorIsMissing(): void
    {
        $params = new InfoVersionParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447701);

        $params->withoutExpectedPackage('/package');
    }

    public function testExpectedPackagesInputIsValidatedWhenPackageNameIsMissing(): void
    {
        $params = new InfoVersionParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447701);

        $params->withoutExpectedPackage('vendor/');
    }

    public function testExpectedPackagesInputIsValidatedWhenPackageSeparatorIsMissing(): void
    {
        $params = new InfoVersionParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447701);

        $params->withoutExpectedPackage('vendor-package-name');
    }

    public function testExpectedPackagesInputIsValidatedWhenConstraintIsEmpty(): void
    {
        $params = new InfoVersionParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447702);

        $params->withExpectedPackage('vendor/package', '');
    }
}
