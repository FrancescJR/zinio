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


}
