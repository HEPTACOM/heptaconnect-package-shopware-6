<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoParams;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExpectationFailedException;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoParams
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Info\InfoResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\InfoAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class ExpectedPackagesTest extends AbstractActionTestCase
{
    public function testVersionCanBeMatchedWithConstraints(): void
    {
        $action = $this->createAction(InfoAction::class);
        $version = $action->getInfo(new InfoParams())->getVersion();
        $params = new InfoParams();

        $params = $params->withExpectedPackage('shopware/core', $version);

        $action->getInfo($params);
        $action->getInfo($params->withAddedExpectedPackage('shopware/core', '>=' . $version));
        $action->getInfo($params->withAddedExpectedPackage('shopware/core', '<=' . $version));

        $params = $params->withoutExpectedPackage('shopware/core');
        $params = $params->withExpectedPackage('shopware/core', '>' . $version);

        static::expectException(ExpectationFailedException::class);

        $action->getInfo($params);
    }

    public function testExpectedPackagesInputIsValidatedWhenPackageIsEmpty(): void
    {
        $params = new InfoParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447700);

        $params->withoutExpectedPackage('');
    }

    public function testExpectedPackagesInputIsValidatedWhenPackageVendorIsMissing(): void
    {
        $params = new InfoParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447701);

        $params->withoutExpectedPackage('/package');
    }

    public function testExpectedPackagesInputIsValidatedWhenPackageNameIsMissing(): void
    {
        $params = new InfoParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447701);

        $params->withoutExpectedPackage('vendor/');
    }

    public function testExpectedPackagesInputIsValidatedWhenPackageSeparatorIsMissing(): void
    {
        $params = new InfoParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447701);

        $params->withoutExpectedPackage('vendor-package-name');
    }

    public function testExpectedPackagesInputIsValidatedWhenConstraintIsEmpty(): void
    {
        $params = new InfoParams();

        static::expectException(\UnexpectedValueException::class);
        static::expectExceptionCode(1680447702);

        $params->withExpectedPackage('vendor/package', '');
    }
}
