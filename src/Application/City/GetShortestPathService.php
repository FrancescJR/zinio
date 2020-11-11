<?php
declare(strict_types=1);

namespace Zinio\Cesc\Application\City;

use Zinio\Cesc\Application\City\PlainObject\CityPO;
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

        // Real logic of the solution (insert internet algorithm here...)

        $citiesPOs = [];

        foreach ($cities as $city) {
            $citiesPOs[] = new CityPO($city);
        }

        return $citiesPOs;
    }

}
