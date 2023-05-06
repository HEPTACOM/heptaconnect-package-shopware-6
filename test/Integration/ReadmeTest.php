<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\GenericAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Contract\AuthenticatedHttpClientInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityDeleteAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchIdAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityUpdateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\EntityClient;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\GenericClient;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\AdminApi\Factory;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Support\Package\BaseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationBucket
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationBucketCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\AbstractFieldAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\FilterAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSorting
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FilterCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\AbstractFieldFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Filter\EqualsFilter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\SortingContract
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Generic\GenericPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\Generic\GenericResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\GenericAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Support\ActionClientUtils
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Authentication
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\MemoryApiConfigurationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchIdAction
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\EntityClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\GenericClient
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericPayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\Support\Action\Generic\AbstractGenericResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\LetterCase
 */
final class ReadmeTest extends TestCase
{
    public function testPropertyGroupExample(): void
    {
        $entityClient = $this->createEntityClient();
        $propertyGroupId = $entityClient->create('property_group', [
            'name' => 'Color',
            'sortingType' => 'position',
            'displayType' => 'color',
            'options' => [[
                'position' => 1,
                'name' => 'Red',
                'colorHexCode' => '#aa0000',
            ], [
                'position' => 2,
                'name' => 'Green',
                'colorHexCode' => '#00aa00',
            ], [
                'position' => 3,
                'name' => 'Blue',
                'colorHexCode' => '#0000aa',
            ]],
        ]);

        $colorNamesByName = $entityClient->groupFieldByField('property_group_option', 'colorHexCode', 'name', new EqualsFilter('group.id', $propertyGroupId));
        $output = \var_export($colorNamesByName, true);

        $varDump = <<<'DUMP'
array (
  '#0000aa' => 'Blue',
  '#00aa00' => 'Green',
  '#aa0000' => 'Red',
)
DUMP;
        static::assertSame($varDump, $output);

        $countryIsos = $entityClient->aggregate('country', new TermsAggregation('countries', 'iso'))->buckets->getKeys();
        $output = \var_export($countryIsos->asArray(), true);

        $varDump = <<<'DUMP'
array (
  0 => 'AD',
  1 => 'AE',
  2 => 'AF',
  3 => 'AG',
  4 => 'AI',
DUMP;
        static::assertStringStartsWith($varDump, $output);
    }

    public function testGenericClientExample(): void
    {
        $client = $this->createGenericClient();
        // low amount of parameters
        $output = \var_export($client->get('_info/version'), true);
        $version = Factory::getShopwareVersion();
        $varDump = <<<DUMP
array (
  'version' => '$version',
)
DUMP;

        static::assertSame($varDump, $output);

        // query parameters
        $systemConfig = $client->get('_action/system-config', [
            'domain' => 'core.update',
        ]);
        \ksort($systemConfig);
        $output = var_export($systemConfig, true);
        $varDump = <<<'DUMP'
array (
  'core.update.apiUri' => 'https://update-api.shopware.com',
  'core.update.channel' => 'stable',
  'core.update.code' => '',
)
DUMP;
        static::assertSame($varDump, $output);

        $client = $this->createMock(AuthenticatedHttpClientInterface::class);
        $client->method('sendRequest')->willReturnCallback(static function (RequestInterface $request): ResponseInterface {
            static::assertSame('application/json', $request->getHeaderLine('content-type'));
            static::assertSame('{"key":"value"}', (string) $request->getBody());

            return BaseFactory::createResponseFactory()
                ->createResponse()
                ->withBody(BaseFactory::createJsonStreamUtility()->fromPayloadToStream([]));
        });

        $client = $this->createGenericClient($client);

        // JSON body
        $client->post('_action/system-config', [
            'key' => 'value',
        ]);

        $client = $this->createMock(AuthenticatedHttpClientInterface::class);
        $client->method('sendRequest')->willReturnCallback(static function (RequestInterface $request): ResponseInterface {
            static::assertSame('1', $request->getHeaderLine('sw-skip-trigger-flow'));

            return BaseFactory::createResponseFactory()
                ->createResponse()
                ->withBody(BaseFactory::createJsonStreamUtility()->fromPayloadToStream([]));
        });

        $client = $this->createGenericClient($client);

        // header support
        $client->post('_action/order/00000000000000000000000000000000/state/complete', [], [], [
            // do not run flows to silently update order state
            'sw-skip-trigger-flow' => '1',
        ]);
    }

    private function createEntityClient(): EntityClient
    {
        return new EntityClient(
            Factory::createActionClass(EntitySearchAction::class, new CriteriaFormatter()),
            Factory::createActionClass(EntitySearchIdAction::class, new CriteriaFormatter()),
            Factory::createActionClass(EntityCreateAction::class),
            Factory::createActionClass(EntityGetAction::class),
            Factory::createActionClass(EntityUpdateAction::class),
            Factory::createActionClass(EntityDeleteAction::class)
        );
    }

    private function createGenericClient(?AuthenticatedHttpClientInterface $client = null): GenericClient
    {
        return new GenericClient(new GenericAction(Factory::createActionClientUtils($client)));
    }
}
