<?php
declare(strict_types=1);

namespace Domain\City;


use PHPUnit\Framework\TestCase;
use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\ValueObject\CityCoordinates;
use Zinio\Cesc\Domain\City\ValueObject\CityName;

class CityTest extends TestCase
{
    public function testGetters()
    {
        $coordinates = self::createMock(CityCoordinates::class);
        $name        = self::createMock(CityName::class);

        $city = new City(
            $name,
            $coordinates
        );

        self::assertEquals($name, $city->getName());
        self::assertEquals($coordinates, $city->getCoordinates());
    }
}
