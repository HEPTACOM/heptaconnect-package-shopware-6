<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\AverageAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\CountAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\EntityAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\HistogramAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\MaximumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\MinimumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\StatisticsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\SumAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSorting;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidLimitQueryException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\NotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action\AbstractActionTestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationContract
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\AggregationResultCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\AbstractFieldAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\EntityAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\HistogramAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Aggregation\TermsAggregation
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\CountSorting
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSorting
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\SortingCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\SortingContract
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidLimitQueryException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\NotFoundException
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\PluginNotInstalledValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ResourceNotFoundValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\ServerErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\WriteTypeIntendErrorValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\PackageExpectation\Support\ExpectedPackagesAwareTrait
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class EntitySearchTest extends AbstractActionTestCase
{
    public function testFetchEntityWithEmptyCriteria(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria('country', new Criteria()));

        static::assertNotSame([], $result->getData()->asArray());
        static::assertCount($result->getTotal(), $result->getData());

        foreach ($result->getData() as $entity) {
            $id = $entity['id'];

            static::assertIsString($id);
        }
    }

    public function testFetchEntityWithLimitCriteria(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria('country', (new Criteria())->withLimit(1)));

        static::assertNotSame([], $result->getData()->asArray());
        static::assertCount(1, $result->getData());
    }

    public function testFetchWithDontTotalCriteria(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(
            new EntitySearchCriteria(
                'country',
                (new Criteria())
                ->withLimit(1)
                ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NONE)
            )
        );

        static::assertNotSame([], $result->getData()->asArray());
        static::assertCount(1, $result->getData());
        static::assertSame(1, $result->getTotal());
    }

    public function testFetchWithExactTotalCriteria(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(
            new EntitySearchCriteria(
                'country',
                (new Criteria())
                    ->withLimit(1)
                    ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_EXACT)
            )
        );

        static::assertNotSame([], $result->getData()->asArray());
        static::assertCount(1, $result->getData());
        static::assertGreaterThan(200, $result->getTotal());
    }

    public function testFetchWithNextPageTotalCriteria(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(
            new EntitySearchCriteria(
                'country',
                (new Criteria())
                    ->withLimit(1)
                    ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NEXT_PAGES)
            )
        );

        static::assertNotSame([], $result->getData()->asArray());
        static::assertCount(1, $result->getData());
        static::assertLessThan(200, $result->getTotal());
        static::assertGreaterThan(1, $result->getTotal());
    }

    public function testFetchWithSearchTerm(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(
            new EntitySearchCriteria(
                'country',
                (new Criteria())->withTerm('de')
            )
        );

        static::assertNotSame([], $result->getData()->asArray());
        static::assertLessThan(20, $result->getTotal());

        $germanyFound = false;

        foreach ($result->getData() as $entity) {
            if ($entity->iso === 'DE') {
                $germanyFound = true;
            }
        }

        static::assertTrue($germanyFound);
    }

    public function testFetchWithIncludes(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria(
            'country',
            (new Criteria())->withAddedIncludes('country', ['id', 'iso'])->withLimit(1)
        ));

        static::assertNotSame([], $result->getData()->asArray());

        $fields = \array_keys($result->getData()->first()->getArrayCopy());

        static::assertEqualsCanonicalizing([
            'apiAlias',
            'id',
            'iso',
        ], $fields);
    }

    public function testFetchWithEmptyIncludes(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria(
            'country',
            (new Criteria())->withAddedIncludes('country', [])->withLimit(1)
        ));

        static::assertNotSame([], $result->getData()->asArray());

        $fields = \array_keys($result->getData()->first()->getArrayCopy());

        static::assertEqualsCanonicalizing([
            'apiAlias',
        ], $fields);
    }

    public function testFetchWithIdSorting(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $criteria = (new Criteria())->withFieldSort('id');
        $ascResult = $client->search(new EntitySearchCriteria('country', $criteria));
        $descResult = $client->search(new EntitySearchCriteria(
            'country',
            $criteria->withoutFieldSort('id')->withFieldSort('id', FieldSorting::DESCENDING)
        ));

        $ascIds = \array_column($ascResult->getData()->asArray(), 'id');
        $descIds = \array_column($descResult->getData()->asArray(), 'id');

        static::assertSame(\array_reverse($descIds), $ascIds);
    }

    public function testFetchWithCountSorting(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $criteria = (new Criteria())->withCountSort('states.id');
        $result = $client->search(new EntitySearchCriteria('country', $criteria));

        $first = $result->getData()->shift();
        $second = $result->getData()->shift();
        $third = $result->getData()->shift();

        static::assertSame($first->iso, 'GB'); // 224 states
        static::assertSame($second->iso, 'US'); // 50 states
        static::assertSame($third->iso, 'DE'); // 16 states
    }

    public function testFetchWithGrouping(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $criteria = (new Criteria())->withAddedGroupField('countryId');
        $groupedResults = $client->search(new EntitySearchCriteria('country-state', $criteria));
        $criteria = $criteria->withoutGroupField('countryId');
        $nonGroupedResults = $client->search(new EntitySearchCriteria('country-state', $criteria));

        static::assertSame(3, $groupedResults->getTotal());
        static::assertGreaterThan($groupedResults->getTotal(), $nonGroupedResults->getTotal());
    }

    public function testFetchWithInvalidLimitParameter(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());

        static::expectException(InvalidLimitQueryException::class);

        $client->search(new EntitySearchCriteria('country', (new Criteria())->withLimit(0)));
    }

    public function testEntityFormatWithEntityThatContainsSeparator(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria('sales-channel', new Criteria()));

        foreach ($result->getData() as $entity) {
            static::assertSame('sales_channel', $entity->apiAlias);
        }
    }

    public function testEntityFormatWithWrongEntityNameSeparatorFails(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());

        static::expectException(NotFoundException::class);

        $client->search(new EntitySearchCriteria('sales_channel', new Criteria()));
    }

    public function testCountAggregation(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria(
            'country',
            (new Criteria())
                ->withLimit(1)
                ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_EXACT)
                ->withAddedAggregation(new CountAggregation('count', 'id'))
        ));

        static::assertSame($result->getTotal(), $result->getAggregations()['count']->count);
    }

    public function testStatisticsAggregation(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria(
            'country',
            (new Criteria())
                ->withLimit(1)
                ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NONE)
                ->withAddedAggregation(new AverageAggregation('average', 'position'))
                ->withAddedAggregation(new SumAggregation('sum', 'position'))
                ->withAddedAggregation(new MinimumAggregation('minimum', 'position'))
                ->withAddedAggregation(new MaximumAggregation('maximum', 'position'))
                ->withAddedAggregation(new StatisticsAggregation('statistics', 'position'))
        ));

        static::assertEquals($result->getAggregations()['statistics']->avg, $result->getAggregations()['average']->avg);
        static::assertEquals($result->getAggregations()['statistics']->sum, $result->getAggregations()['sum']->sum);
        static::assertEquals($result->getAggregations()['statistics']->min, $result->getAggregations()['minimum']->min);
        static::assertEquals($result->getAggregations()['statistics']->max, $result->getAggregations()['maximum']->max);
    }

    public function testEntityAggregation(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria(
            'country-state',
            (new Criteria())
                ->withLimit(1)
                ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NONE)
                ->withAddedAggregation(new EntityAggregation('countries', 'countryId', 'country'))
                ->withAddedAggregation(new TermsAggregation('countryIds', 'countryId'))
        ));

        static::assertCount(3, $result->getAggregations()['countries']->entities);
        static::assertEqualsCanonicalizing(
            \array_column($result->getAggregations()['countryIds']->buckets, 'key'),
            \array_column($result->getAggregations()['countries']->entities->asArray(), 'id')
        );
    }

    public function testHistogramAggregation(): void
    {
        $client = $this->createAction(EntitySearchAction::class, new CriteriaFormatter());
        $result = $client->search(new EntitySearchCriteria(
            'country-state',
            (new Criteria())
                ->withLimit(1)
                ->withTotalCountMode(Criteria::TOTAL_COUNT_MODE_NONE)
                ->withAddedAggregation(new CountAggregation('count', 'id'))
                ->withAddedAggregation(new HistogramAggregation('created_minute', 'createdAt', HistogramAggregation::INTERVAL_MINUTE))
                ->withAddedAggregation(new HistogramAggregation('created_hour', 'createdAt', HistogramAggregation::INTERVAL_HOUR))
                ->withAddedAggregation(new HistogramAggregation('created_day', 'createdAt', HistogramAggregation::INTERVAL_DAY))
        ));

        static::assertSame($result->getAggregations()['count']->count, $result->getAggregations()['created_minute']->buckets[0]['count']);
        static::assertSame($result->getAggregations()['count']->count, $result->getAggregations()['created_hour']->buckets[0]['count']);
        static::assertSame($result->getAggregations()['count']->count, $result->getAggregations()['created_day']->buckets[0]['count']);
    }
}
