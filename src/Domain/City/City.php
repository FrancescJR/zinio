<?php
declare(strict_types=1);

namespace Zinio\Cesc\Domain\City;


use Zinio\Cesc\Domain\City\ValueObject\CityCoordinates;
use Zinio\Cesc\Domain\City\ValueObject\CityName;

class City
{
    /**
     * @var CityName
     */
    private $name;

    /**
     * @var CityCoordinates
     */
    private $coordinates;

    /**
     * City constructor.
     *
     * @param CityName $cityName
     * @param CityCoordinates $cityCoordinates
     */
    public function __construct(
        CityName $cityName,
        CityCoordinates $cityCoordinates
    ) {
        $this->name        = $cityName;
        $this->coordinates = $cityCoordinates;
    }

    /**
     * @return CityName
     */
    public function getName(): CityName
    {
        return $this->name;
    }

    /**
     * @return CityCoordinates
     */
    public function getCoordinates(): CityCoordinates
    {
        return $this->coordinates;
    }

    /**
     * @param City $city
     *
     * @return float
     */
    public function getDistanceFromCity(City $city): float
    {
        // convert from degrees to radians
        $latFrom = deg2rad($this->coordinates->getLatitude());
        $lonFrom = deg2rad($this->coordinates->getLongitude());
        $latTo = deg2rad( $city->getCoordinates()->getLatitude());
        $lonTo = deg2rad( $city->getCoordinates()->getLongitude());

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                               cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * 6371;
    }


}
