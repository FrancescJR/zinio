<?php
declare(strict_types=1);

namespace Domain\City;


use PHPUnit\Framework\TestCase;
use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\ValueObject\CityCoordinates;
use Zinio\Cesc\Domain\City\ValueObject\CityName;

class CityTest extends TestCase
{
    private const ERROR_ALLOWED = 0.05;
    private const SHANGHAI_TAIPEI_KM = 680;
    private const SHANGHAI_USHUAIA_KM = 17271;
    private const USHUAIA_TAIPEI_KM = 16597;
    private const QUEENSTOWN_BARCELONA_KM = 18837;

    public function testGetters()
    {
        $coordinates = self::createMock(CityCoordinates::class);
        $name = self::createMock(CityName::class);

        $city = new City(
            $name,
            $coordinates
        );

        self::assertEquals($name, $city->getName());
        self::assertEquals($coordinates, $city->getCoordinates());
    }

    public function testGetDistanceFromCity()
    {
        // googling information.
        $shanghaiCoordinates = self::createMock(CityCoordinates::class);
        $shanghaiCoordinates->method("getLatitude")->willReturn(31.23);
        $shanghaiCoordinates->method("getLongitude")->willReturn(121.47);

        $ushuaiaCoordinates = self::createMock(CityCoordinates::class);
        $ushuaiaCoordinates->method("getLatitude")->willReturn(-54.80);
        $ushuaiaCoordinates->method("getLongitude")->willReturn(-68.30);

        $taipeiCoordinates = self::createMock(CityCoordinates::class);
        $taipeiCoordinates->method("getLatitude")->willReturn(25.33);
        $taipeiCoordinates->method("getLongitude")->willReturn(121.56);

        $barcelonaCoordinates = self::createMock(CityCoordinates::class);
        $barcelonaCoordinates->method("getLatitude")->willReturn(41.38);
        $barcelonaCoordinates->method("getLongitude")->willReturn(2.17);

        $queenstownCoordinates = self::createMock(CityCoordinates::class);
        $queenstownCoordinates->method("getLatitude")->willReturn(-45.03);
        $queenstownCoordinates->method("getLongitude")->willReturn(168.66);

        $shanghai = new City(
            self::createMock(CityName::class),
            $shanghaiCoordinates
        );

        $ushuaia = new City(
            self::createMock(CityName::class),
            $ushuaiaCoordinates
        );

        $taipei = new City(
            self::createMock(CityName::class),
            $taipeiCoordinates
        );

        $barcelona = new City(
            self::createMock(CityName::class),
            $barcelonaCoordinates
        );

        $queenstown = new City(
            self::createMock(CityName::class),
            $queenstownCoordinates
        );

        self::assertEquals(
            $shanghai->getDistanceFromCity($taipei),
            $taipei->getDistanceFromCity($shanghai)
        );

        // Google says 689km
        self::assertThat(
            $shanghai->getDistanceFromCity($taipei),
            self::logicalAnd(
                self::greaterThan(self::SHANGHAI_TAIPEI_KM  - self::SHANGHAI_TAIPEI_KM * self::ERROR_ALLOWED),
                self::lessThan(self::SHANGHAI_TAIPEI_KM  + self::SHANGHAI_TAIPEI_KM * self::ERROR_ALLOWED)
            )
        );

        self::assertEquals(
            $taipei->getDistanceFromCity($ushuaia),
            $ushuaia->getDistanceFromCity($taipei)
        );

        self::assertThat(
            $taipei->getDistanceFromCity($ushuaia),
            self::logicalAnd(
                self::greaterThan(self::USHUAIA_TAIPEI_KM  - self::USHUAIA_TAIPEI_KM * self::ERROR_ALLOWED),
                self::lessThan(self::USHUAIA_TAIPEI_KM  + self::USHUAIA_TAIPEI_KM * self::ERROR_ALLOWED)
            )
        );

        self::assertThat(
            $shanghai->getDistanceFromCity($ushuaia),
            self::logicalAnd(
                self::greaterThan(self::SHANGHAI_USHUAIA_KM  - self::SHANGHAI_USHUAIA_KM * self::ERROR_ALLOWED),
                self::lessThan(self::SHANGHAI_USHUAIA_KM  + self::SHANGHAI_USHUAIA_KM * self::ERROR_ALLOWED)
            )
        );

        self::assertThat(
            $queenstown->getDistanceFromCity($barcelona),
            self::logicalAnd(
                self::greaterThan(self::QUEENSTOWN_BARCELONA_KM  - self::QUEENSTOWN_BARCELONA_KM * self::ERROR_ALLOWED),
                self::lessThan(self::QUEENSTOWN_BARCELONA_KM  + self::QUEENSTOWN_BARCELONA_KM * self::ERROR_ALLOWED)
            )
        );
    }


}
