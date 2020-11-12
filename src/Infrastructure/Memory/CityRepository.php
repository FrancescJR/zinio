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
            $distance = $this->getDistanceBetweenCities($city, $toCity);
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

    /**
     * @param City $cityFrom
     * @param City $cityTo
     *
     * @return float
     */
    public function getDistanceBetweenCities(City $cityFrom, City $cityTo): float
    {
        // convert from degrees to radians
        $latFrom = deg2rad($cityFrom->getCoordinates()->getLatitude());
        $lonFrom = deg2rad($cityFrom->getCoordinates()->getLongitude());
        $latTo   = deg2rad($cityTo->getCoordinates()->getLatitude());
        $lonTo   = deg2rad($cityTo->getCoordinates()->getLongitude());

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                               cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        # 6371 is the earth radius in km, so the result is in km.
        return $angle * 6371;

    }
}
