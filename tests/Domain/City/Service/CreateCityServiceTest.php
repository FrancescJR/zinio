<?php
declare(strict_types=1);

namespace Domain\City\Service;


use PHPUnit\Framework\TestCase;
use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\Service\CreateCitiesService;

class CreateCityServiceTest extends TestCase
{
    public function testSuccess(): void
    {
        $citiesArray = [
            ["barcelona", 3, 3]
        ];

        $service = new CreateCitiesService();

        $cities = $service->execute($citiesArray);


        self::assertCount(1, $cities);
        self::assertEquals(City::class, get_class($cities[0]));
    }

}
