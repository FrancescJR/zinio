<?php
declare(strict_types=1);

namespace Zinio\Cesc\Application\City;

use Zinio\Cesc\Application\City\PlainObject\CityPO;
use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\Exception\InvalidValueException;
use Zinio\Cesc\Domain\City\Service\CreateCitiesService;

class GetShortestPathService
{
    private $createCitiesService;

    public function __construct(CreateCitiesService $createCitiesService)
    {
        $this->createCitiesService = $createCitiesService;
    }

    /**
     * @param array $citiesText
     *
     * @return CityPO[]
     * @throws InvalidValueException
     */
    public function execute(array $citiesText): array
    {
        $cities = $this->createCitiesService->execute($citiesText);

        // straight solution, calculate shorter path from the first and so on.
        $startingCity = $cities[0];

        $citiesPOs = [
            new CityPO($startingCity)
        ];

        unset($cities[0]);

        while($cities) {
            $nextCityIndexInArray = $this->getClosestCityIndexInArray($startingCity, $cities);
            $citiesPOs[] = new CityPO($cities[$nextCityIndexInArray]);
            unset($cities[$nextCityIndexInArray]);
        }

        return $citiesPOs;
    }

    /**
     * @param City $startingCity
     * @param City[] $cities
     *
     * @return int|null
     */
    private function getClosestCityIndexInArray(City $startingCity, array $cities): ?int
    {
        $closestCity = null;
        $nextCity = null;
        $shortestDistance = 0;

        foreach($cities as $key => $city) {
            $distance = $startingCity->getDistanceFromCity($city);
            if (!$closestCity or $distance <= $shortestDistance) {
                $closestCity = $key;
                $shortestDistance = $distance;
            }
        }

        return $closestCity;
    }


}
