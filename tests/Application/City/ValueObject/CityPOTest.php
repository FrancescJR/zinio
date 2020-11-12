<?php
declare(strict_types=1);

namespace Application\City\ValueObject;


use PHPUnit\Framework\TestCase;
use Zinio\Cesc\Application\City\PlainObject\CityPO;
use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\ValueObject\CityName;

class CityPOTest extends TestCase
{
    private const CITY_NAME = 'city name';

    public function testToString(): void
    {
        $cityName = self::createMock(CityName::class);
        $cityName->method("value")->willReturn(self::CITY_NAME);

        $city = self::createMock(City::class);
        $city->method("getName")->willReturn($cityName);

        $cityPO = new CityPO($city);

        self::assertStringEndsWith("\n", (string)$cityPO);

        self::assertStringContainsString(self::CITY_NAME, (string)$cityPO);
    }

}
