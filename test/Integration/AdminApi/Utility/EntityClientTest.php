<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Utility;

use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria;
use Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityDeleteAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchIdAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityUpdateAction;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ResourceNotFoundException;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\EntityClient;
use Heptacom\HeptaConnect\Package\Shopware6\Test\Integration\AdminApi\Action\AbstractActionTestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Criteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\EntityCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSorting
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\Contract\FieldSortingCollection
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\EntitySearch\CriteriaFormatter
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\AbstractActionClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\ApiConfiguration
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\AuthenticatedHttpClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\Exception\AuthenticationFailed
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Authentication\PortalNodeStorageAuthenticationStorage
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\AbstractEntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityDelete\EntityDeleteCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityDelete\EntityDeleteResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityGet\EntityGetResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearchId\EntitySearchIdResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchCriteria
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntitySearch\EntitySearchResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdatePayload
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityUpdate\EntityUpdateResult
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityCreateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityDeleteAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityGetAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntitySearchIdAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\EntityUpdateAction
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Exception\EntityReferenceLocationFormatInvalidException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\AbstractRequestException
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ResourceNotFoundException
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
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\LetterCase
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Utility\EntityClient
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Support\JsonStreamUtility
 */
final class EntityClientTest extends AbstractActionTestCase
{
    public function testIterateCountries(): void
    {
        $client = $this->createEntityClient();
        $countries = [];

        foreach ($client->iterate('country', (new Criteria())->withLimit(50)) as $country) {
            $countries[$country->id] = $country;
        }

        static::assertCount($client->count('country'), $countries);

        $todoCountries = $countries;

        foreach ($client->iterateIds('country', (new Criteria())->withLimit(50)) as $countryId) {
            static::assertArrayHasKey($countryId, $countries);

            unset($todoCountries[$countryId]);
        }

        static::assertSame([], $todoCountries);

        $todoCountries = $countries;

        foreach ($client->search('country', (new Criteria())->withIds(\array_keys($countries))) as $country) {
            static::assertArrayHasKey($country->id, $countries);

            unset($todoCountries[$country->id]);
        }

        static::assertSame([], $todoCountries);

        $todoCountries = $countries;

        foreach ($client->searchIds('country', (new Criteria())->withIds(\array_keys($countries))) as $countryId) {
            static::assertArrayHasKey($countryId, $countries);

            unset($todoCountries[$countryId]);
        }

        static::assertSame([], $todoCountries);
    }

    public function testGetFirstCountry(): void
    {
        $client = $this->createEntityClient();
        $countryId = $client->getFirstId('country', new Criteria());

        static::assertIsString($countryId);

        $country = $client->get('country', $countryId);

        static::assertSame($countryId, $country->id);
    }

    public function testThatAnAlreadyDeletedEntryIsNotBadOnSecondTryToDelete(): void
    {
        $client = $this->createEntityClient();
        $tagId = $client->create('tag', [
            'name' => \bin2hex(\random_bytes(24)),
        ]);

        $client->delete('tag', $tagId);
        // this will throw an exception but this is muted
        $client->delete('tag', $tagId);

        static::assertFalse($client->exists('tag', $tagId));
        static::expectException(ResourceNotFoundException::class);

        // this exception is not muted
        $client->get('tag', $tagId);
    }

    public function testEntityLifecycle(): void
    {
        $client = $this->createEntityClient();
        $tagId = $client->create('tag', [
            'name' => \bin2hex(\random_bytes(24)),
        ]);

        static::assertTrue($client->exists('tag', $tagId));

        $newName = \bin2hex(\random_bytes(24));
        $client->update('tag', [
            'id' => $tagId,
            'name' => $newName,
        ]);

        static::assertSame($newName, $client->get('tag', $tagId)->name);

        $client->delete('tag', $tagId);

        static::assertFalse($client->exists('tag', $tagId));
    }

    private function createEntityClient(): EntityClient
    {
        return new EntityClient(
            $this->createAction(EntitySearchAction::class, new CriteriaFormatter()),
            $this->createAction(EntitySearchIdAction::class, new CriteriaFormatter()),
            $this->createAction(EntityCreateAction::class),
            $this->createAction(EntityGetAction::class),
            $this->createAction(EntityUpdateAction::class),
            $this->createAction(EntityDeleteAction::class)
        );
    }
}
