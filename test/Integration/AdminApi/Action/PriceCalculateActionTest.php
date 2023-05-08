<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate\PriceCalculatePayload;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\PriceCalculateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSorting
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\SortingCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\SortingContract
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate\PriceCalculatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\PriceCalculate\PriceCalculateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\PriceCalculateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseErrorHandler
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\CartMissingOrderRelationValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\InvalidUuidValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MediaDuplicatedFileNameValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\NotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\UnmappedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\ErrorHandling\JsonResponseValidator\WriteUnexpectedFieldValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\AbstractShopwareClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class PriceCalculateActionTest extends TestCase
{
    public function testCalculateGross(): void
    {
        $action = Factory::createActionClass(PriceCalculateAction::class);
        $search = Factory::createActionClass(EntitySearchAction::class, new CriteriaFormatter());
        $taxes = $search->search(new EntitySearchCriteria('tax', (new Criteria())->withFieldSort('id')));

        static::assertGreaterThan(0, $taxes->getTotal());

        foreach ($taxes->getData() as $tax) {
            $result = $action->calculatePrice(new PriceCalculatePayload($tax->id, 42.0));
            $price = $result->getPrice();
            static::assertEquals($tax->taxRate, $price->taxRules[0]->taxRate);
            static::assertEquals(100, $price->taxRules[0]->percentage);
            static::assertEquals($tax->taxRate, $price->calculatedTaxes[0]->taxRate);
            static::assertEquals(42.0, $price->calculatedTaxes[0]->price);
            static::assertEquals(42.0, $price->unitPrice);
            static::assertEquals(42.0, $price->totalPrice);
            static::assertSame(1, $price->quantity);
        }
    }

    public function testCalculateNet(): void
    {
        $action = Factory::createActionClass(PriceCalculateAction::class);
        $search = Factory::createActionClass(EntitySearchAction::class, new CriteriaFormatter());
        $taxes = $search->search(new EntitySearchCriteria('tax', (new Criteria())->withFieldSort('id')));

        static::assertGreaterThan(0, $taxes->getTotal());

        foreach ($taxes->getData() as $tax) {
            $payload = (new PriceCalculatePayload($tax->id, 42.0))
                ->withOutput(PriceCalculatePayload::OUTPUT_NET);
            $result = $action->calculatePrice($payload);
            $price = $result->getPrice();
            static::assertEquals($tax->taxRate, $price->taxRules[0]->taxRate);
            static::assertEquals(100, $price->taxRules[0]->percentage);
            static::assertEquals($tax->taxRate, $price->calculatedTaxes[0]->taxRate);
            static::assertEquals(42.0, $price->calculatedTaxes[0]->price);
            static::assertEquals(42.0, $price->unitPrice);
            static::assertEquals(42.0, $price->totalPrice);
            static::assertSame(1, $price->quantity);
        }
    }

    public function testPreCalculateGross(): void
    {
        $action = Factory::createActionClass(PriceCalculateAction::class);
        $search = Factory::createActionClass(EntitySearchAction::class, new CriteriaFormatter());
        $taxes = $search->search(new EntitySearchCriteria('tax', (new Criteria())->withFieldSort('id')));

        static::assertGreaterThan(0, $taxes->getTotal());

        foreach ($taxes->getData() as $tax) {
            $payload = (new PriceCalculatePayload($tax->id, 42.0))
                ->withCalculated(true)
                ->withOutput(PriceCalculatePayload::OUTPUT_GROSS);
            $result = $action->calculatePrice($payload);
            $price = $result->getPrice();
            static::assertEquals($tax->taxRate, $price->taxRules[0]->taxRate);
            static::assertEquals(100, $price->taxRules[0]->percentage);
            static::assertEquals($tax->taxRate, $price->calculatedTaxes[0]->taxRate);
            static::assertEquals(42.0, $price->calculatedTaxes[0]->price);
            static::assertEquals(42.0, $price->unitPrice);
            static::assertEquals(42.0, $price->totalPrice);
            static::assertSame(1, $price->quantity);
        }
    }

    public function testCalculateQuantity10(): void
    {
        $action = Factory::createActionClass(PriceCalculateAction::class);
        $search = Factory::createActionClass(EntitySearchAction::class, new CriteriaFormatter());
        $taxes = $search->search(new EntitySearchCriteria('tax', (new Criteria())->withFieldSort('id')));

        static::assertGreaterThan(0, $taxes->getTotal());

        foreach ($taxes->getData() as $tax) {
            $payload = (new PriceCalculatePayload($tax->id, 42.0))
                ->withQuantity(10);
            $result = $action->calculatePrice($payload);
            $price = $result->getPrice();
            static::assertEquals($tax->taxRate, $price->taxRules[0]->taxRate);
            static::assertEquals(100, $price->taxRules[0]->percentage);
            static::assertEquals($tax->taxRate, $price->calculatedTaxes[0]->taxRate);
            static::assertEquals(420.0, $price->calculatedTaxes[0]->price);
            static::assertEquals(42.0, $price->unitPrice);
            static::assertEquals(420.0, $price->totalPrice);
            static::assertSame($payload->getQuantity(), $price->quantity);
        }
    }
}
