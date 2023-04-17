<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Test\Unit;

use Heptacom\HeptaConnect\Package\Shopware6\Contract\Entity;
use Heptacom\HeptaConnect\Package\Shopware6\Contract\EntityCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Contract\Entity
 * @covers \Heptacom\HeptaConnect\Package\Shopware6\Contract\EntityCollection
 */
final class EntityTest extends TestCase
{
    private const COUNTRIES = [
        [
            'name' => 'Wallis and Futuna',
            'iso' => 'WF',
            'position' => 10,
            'taxFree' => false,
            'active' => true,
            'shippingAvailable' => true,
            'iso3' => 'WLF',
            'displayStateInRegistration' => false,
            'forceStateInRegistration' => false,
            'companyTaxFree' => false,
            'checkVatIdPattern' => false,
            'vatIdPattern' => null,
            'states' => null,
            'translations' => null,
            'orderAddresses' => null,
            'customerAddresses' => null,
            'salesChannelDefaultAssignments' => null,
            'salesChannels' => null,
            'taxRules' => null,
            'currencyCountryRoundings' => null,
            'taxFreeFrom' => null,
            'vatIdRequired' => null,
            '_uniqueIdentifier' => '02858cb12e754f84842f781b5b4f61ce',
            'versionId' => null,
            'translated' => [
                'name' => 'Wallis and Futuna',
                'customFields' => [],
            ],
            'createdAt' => '2022-04-01T17:20:19.269+00:00',
            'updatedAt' => null,
            'extensions' => [
                'foreignKeys' => [
                    'apiAlias' => null,
                    'extensions' => [],
                ],
            ],
            'id' => '02858cb12e754f84842f781b5b4f61ce',
            'customFields' => null,
            'apiAlias' => 'country',
        ],
        [
            'name' => 'Greece',
            'iso' => 'GR',
            'position' => 10,
            'taxFree' => false,
            'active' => true,
            'shippingAvailable' => true,
            'iso3' => 'GRC',
            'displayStateInRegistration' => false,
            'forceStateInRegistration' => false,
            'companyTaxFree' => false,
            'checkVatIdPattern' => false,
            'vatIdPattern' => '(EL|GR)?[0-9]{9}',
            'states' => null,
            'translations' => null,
            'orderAddresses' => null,
            'customerAddresses' => null,
            'salesChannelDefaultAssignments' => null,
            'salesChannels' => null,
            'taxRules' => null,
            'currencyCountryRoundings' => null,
            'taxFreeFrom' => null,
            'vatIdRequired' => null,
            '_uniqueIdentifier' => '0463b20cfc3147eeb8429a791ad8e2b1',
            'versionId' => null,
            'translated' => [
                'name' => 'Greece',
                'customFields' => [],
            ],
            'createdAt' => '2022-04-01T17:20:15.550+00:00',
            'updatedAt' => null,
            'extensions' => [
                'foreignKeys' => [
                    'apiAlias' => null,
                    'extensions' => [],
                ],
            ],
            'id' => '0463b20cfc3147eeb8429a791ad8e2b1',
            'customFields' => null,
            'apiAlias' => 'country',
        ],
        [
            'name' => 'Australia',
            'iso' => 'AU',
            'position' => 10,
            'taxFree' => false,
            'active' => true,
            'shippingAvailable' => true,
            'iso3' => 'AUS',
            'displayStateInRegistration' => false,
            'forceStateInRegistration' => false,
            'companyTaxFree' => false,
            'checkVatIdPattern' => false,
            'vatIdPattern' => null,
            'states' => null,
            'translations' => null,
            'orderAddresses' => null,
            'customerAddresses' => null,
            'salesChannelDefaultAssignments' => null,
            'salesChannels' => null,
            'taxRules' => null,
            'currencyCountryRoundings' => null,
            'taxFreeFrom' => null,
            'vatIdRequired' => null,
            '_uniqueIdentifier' => '05040e2e914e4d28a962b1a8df17edf0',
            'versionId' => null,
            'translated' => [
                'name' => 'Australia',
                'customFields' => [],
            ],
            'createdAt' => '2022-04-01T17:20:15.826+00:00',
            'updatedAt' => null,
            'extensions' => [
                'foreignKeys' => [
                    'apiAlias' => null,
                    'extensions' => [],
                ],
            ],
            'id' => '05040e2e914e4d28a962b1a8df17edf0',
            'customFields' => null,
            'apiAlias' => 'country',
        ],
    ];

    public function testConversionFromArrayToEntityAndCollectionAndBack(): void
    {
        $data = self::COUNTRIES;
        $collection = EntityCollection::fromList($data);

        static::assertSame($collection->asArray(), $data);
    }

    public function testEntityArrayAccessIsSameAsPropertyAccess(): void
    {
        $entity = Entity::fromAssociative(self::COUNTRIES[0]);

        static::assertSame($entity->id, $entity['id']);
    }
}
