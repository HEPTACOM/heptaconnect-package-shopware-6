<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Entity;

use Heptacom\HeptaConnect\Package\Shopware6\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\InvalidLimitQueryException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\NotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action\AbstractActionTestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\CriteriaFormatter
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\FieldIsBlankValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidLimitQueryValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\InvalidTypeValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\MethodNotAllowedValidator
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\JsonResponseValidator\NotFoundValidator
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
}
