<?php
declare(strict_types=1);

namespace Zinio\Cesc\Domain\City;


use Zinio\Cesc\Domain\City\Exception\CityAlreadyExistsException;
use Zinio\Cesc\Domain\City\ValueObject\CityName;

interface CityRepositoryInterface
{

    /**
     * @param City $city
     *
     * @throws CityAlreadyExistsException
     */
    public function save(City $city): void;

    /**
     * @param City $city
     */
    public function remove(City $city): void;

    /**
     * @param City $city
     *
     * @return City|null
     */
    public function getClosestCity(City $city): ?City;

    /**
     * @return int
     */
    public function getCountCities(): int;

}
