<?php
declare(strict_types=1);

namespace Zinio\Cesc\Infrastructure\Memory;

use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\CityRepositoryInterface;
use Zinio\Cesc\Domain\City\Exception\CityAlreadyExistsException;
use Zinio\Cesc\Domain\City\ValueObject\CityName;

class CityRepository implements CityRepositoryInterface
{
    /**
     * @var City[]
     */
    private $cities = [];


    /**
     * @param City $city
     *
     * @throws CityAlreadyExistsException
     */
    public function save(City $city): void
    {
        if (array_key_exists($city->getName()->value(), $this->cities)) {
            throw new CityAlreadyExistsException("City already exists");
        }
        $this->cities[$city->getName()->value()] = $city;
    }

    /**
     * @param City $city
     */
    public function remove(City $city): void
    {
        unset($this->cities[$city->getName()->value()]);
    }

    /**
     * @param City $city
     *
     * @return City|null
     */
    public function getClosestCity(City $city): ?City
    {
        $closestCity      = null;
        $nextCity         = null;
        $shortestDistance = 0;

        foreach ($this->cities as $toCity) {
            $distance = $city->getDistanceFromCity($toCity);
            if ( ! $closestCity or $distance <= $shortestDistance) {
                $closestCity      = $toCity;
                $shortestDistance = $distance;
            }
        }

        return $closestCity;
    }

    public function getCountCities(): int
    {
        return count($this->cities);
    }
}
