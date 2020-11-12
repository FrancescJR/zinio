<?php
declare(strict_types=1);

namespace Zinio\Cesc\Application\City;

use Zinio\Cesc\Application\City\PlainObject\CityPO;
use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\CityRepositoryInterface;
use Zinio\Cesc\Domain\City\Exception\CityAlreadyExistsException;
use Zinio\Cesc\Domain\City\Exception\InvalidValueException;
use Zinio\Cesc\Domain\City\Service\CreateCitiesService;

class GetShortestPathService
{
    private $createCitiesService;
    private $cityRepository;

    public function __construct(CreateCitiesService $createCitiesService, CityRepositoryInterface $cityRepository)
    {
        $this->createCitiesService = $createCitiesService;
        $this->cityRepository      = $cityRepository;
    }

    /**
     * @param array $citiesText
     *
     * @return CityPO[]
     * @throws InvalidValueException|CityAlreadyExistsException
     */
    public function execute(array $citiesText): array
    {

        $cities = $this->createCitiesService->execute($citiesText);

        $startingCity = $cities[0];
        $citiesPOs = [
            new CityPO($startingCity)
        ];
        unset($cities[0]);

        // This part could be in a different application service, but taking into account
        // that we actually are using non persistent memory, let's just do it here.
        // then we would call this service by passing the first city.
        foreach ($cities as $city) {
            $this->cityRepository->save($city);
        }

        echo $this->cityRepository->getCountCities();

        while ($this->cityRepository->getCountCities()) {
            $nextCity = $this->cityRepository->getClosestCity($startingCity);
            $citiesPOs[]          = new CityPO($nextCity);
            $this->cityRepository->remove($nextCity);
            $startingCity = $nextCity;
        }

        return $citiesPOs;
    }

}
