<?php
declare(strict_types=1);

namespace Zinio\Cesc\Domain\City\Service;

use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\Exception\InvalidValueException;
use Zinio\Cesc\Domain\City\ValueObject\CityCoordinates;
use Zinio\Cesc\Domain\City\ValueObject\CityName;

class CreateCitiesService
{
    /**
     * @param array $citiesPlain
     *
     * @return City[]
     * @throws InvalidValueException
     */
    public function execute(array $citiesPlain): array
    {
        $cities = [];
        foreach ($citiesPlain as $cityText) {
            $cities[] = new City(
                new CityName($cityText[0]),
                new CityCoordinates($cityText[1], $cityText[2])
            );
        }

        return $cities;
    }

}
