<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\StoreApi\Action\Contract\CountryGet;

interface CountryGetActionInterface
{
    /**
     * Reads available countries.
     *
     * @throws \Throwable
     */
    public function getCountries(CountryGetCriteria $criteria): CountryGetResult;
}
