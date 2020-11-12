<?php
declare(strict_types=1);

namespace Infrastructure\Memory;


use PHPUnit\Framework\TestCase;
use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\Exception\CityAlreadyExistsException;
use Zinio\Cesc\Domain\City\ValueObject\CityCoordinates;
use Zinio\Cesc\Domain\City\ValueObject\CityName;
use Zinio\Cesc\Infrastructure\Memory\CityRepository;

class CityRepositoryTest extends TestCase
{

    private const ERROR_ALLOWED = 0.05;
    private const SHANGHAI_TAIPEI_KM = 680;
    private const SHANGHAI_USHUAIA_KM = 17271;
    private const USHUAIA_TAIPEI_KM = 16597;
    private const QUEENSTOWN_BARCELONA_KM = 18837;

    private $cityMocks = [];

    public function setUp(): void
    {
        // googling information.
        $shanghaiName = self::createMock(CityName::class);
        $shanghaiName->method('value')->willReturn('shanghai');
        $shanghaiCoordinates = self::createMock(CityCoordinates::class);
        $shanghaiCoordinates->method("getLatitude")->willReturn(31.23);
        $shanghaiCoordinates->method("getLongitude")->willReturn(121.47);

        $ushuaiaName = self::createMock(CityName::class);
        $ushuaiaName->method('value')->willReturn('ushuaia');
        $ushuaiaCoordinates = self::createMock(CityCoordinates::class);
        $ushuaiaCoordinates->method("getLatitude")->willReturn(-54.80);
        $ushuaiaCoordinates->method("getLongitude")->willReturn(-68.30);

        $taipeiName = self::createMock(CityName::class);
        $taipeiName->method('value')->willReturn('taipei');
        $taipeiCoordinates = self::createMock(CityCoordinates::class);
        $taipeiCoordinates->method("getLatitude")->willReturn(25.33);
        $taipeiCoordinates->method("getLongitude")->willReturn(121.56);

        $barcelonaName = self::createMock(CityName::class);
        $barcelonaName->method('value')->willReturn('barcelona');
        $barcelonaCoordinates = self::createMock(CityCoordinates::class);
        $barcelonaCoordinates->method("getLatitude")->willReturn(41.38);
        $barcelonaCoordinates->method("getLongitude")->willReturn(2.17);

        $queenstownName = self::createMock(CityName::class);
        $queenstownName->method('value')->willReturn('queenstown');
        $queenstownCoordinates = self::createMock(CityCoordinates::class);
        $queenstownCoordinates->method("getLatitude")->willReturn(-45.03);
        $queenstownCoordinates->method("getLongitude")->willReturn(168.66);

        $shanghai = new City(
            $shanghaiName,
            $shanghaiCoordinates
        );

        $ushuaia = new City(
            $ushuaiaName,
            $ushuaiaCoordinates
        );

        $taipei = new City(
            $taipeiName,
            $taipeiCoordinates
        );

        $barcelona = new City(
            $barcelonaName,
            $barcelonaCoordinates
        );

        $queenstown = new City(
            $queenstownName,
            $queenstownCoordinates
        );

        $this->cityMocks = [
            'shanghai' => $shanghai,
            'taipei' => $taipei,
            'ushuaia' => $ushuaia,
            'barcelona' => $barcelona,
            'queenstown' => $queenstown
        ];
    }

    public function testSaveAndCountAndRemove(): void
    {

        $cityRepository = new CityRepository();
        $cityRepository->save($this->cityMocks['shanghai']);

        $this->assertEquals(1, $cityRepository->getCountCities());

        $cityRepository->remove($this->cityMocks['shanghai']);
        $this->assertEquals(0, $cityRepository->getCountCities());
    }

    public function testRepeated(): void
    {
        self::expectException(CityAlreadyExistsException::class);
        $cityRepository = new CityRepository();
        $cityRepository->save($this->cityMocks['shanghai']);
        $cityRepository->save($this->cityMocks['shanghai']);
    }

    public function testGetDistanceFromCityAndClosesCity()
    {
        $shanghai = $this->cityMocks['shanghai'];
        $ushuaia = $this->cityMocks['ushuaia'];
        $taipei = $this->cityMocks['taipei'];
        $queenstown = $this->cityMocks['queenstown'];
        $barcelona = $this->cityMocks['barcelona'];


        $cityRepository = new CityRepository();

        // Google says 689km
        self::assertThat(
            $cityRepository->getDistanceBetweenCities($shanghai, $taipei),
            self::logicalAnd(
                self::greaterThan(self::SHANGHAI_TAIPEI_KM  - self::SHANGHAI_TAIPEI_KM * self::ERROR_ALLOWED),
                self::lessThan(self::SHANGHAI_TAIPEI_KM  + self::SHANGHAI_TAIPEI_KM * self::ERROR_ALLOWED)
            )
        );

        self::assertThat(
            $cityRepository->getDistanceBetweenCities($taipei, $ushuaia),
            self::logicalAnd(
                self::greaterThan(self::USHUAIA_TAIPEI_KM  - self::USHUAIA_TAIPEI_KM * self::ERROR_ALLOWED),
                self::lessThan(self::USHUAIA_TAIPEI_KM  + self::USHUAIA_TAIPEI_KM * self::ERROR_ALLOWED)
            )
        );

        self::assertThat(
            $cityRepository->getDistanceBetweenCities($shanghai, $ushuaia),
            self::logicalAnd(
                self::greaterThan(self::SHANGHAI_USHUAIA_KM  - self::SHANGHAI_USHUAIA_KM * self::ERROR_ALLOWED),
                self::lessThan(self::SHANGHAI_USHUAIA_KM  + self::SHANGHAI_USHUAIA_KM * self::ERROR_ALLOWED)
            )
        );

        self::assertThat(
            $cityRepository->getDistanceBetweenCities($queenstown, $barcelona),
            self::logicalAnd(
                self::greaterThan(self::QUEENSTOWN_BARCELONA_KM  - self::QUEENSTOWN_BARCELONA_KM * self::ERROR_ALLOWED),
                self::lessThan(self::QUEENSTOWN_BARCELONA_KM  + self::QUEENSTOWN_BARCELONA_KM * self::ERROR_ALLOWED)
            )
        );
    }

    public function testGetClosestCity(): void
    {
        $cityRepository = new CityRepository();
        foreach($this->cityMocks as $city) {
            $cityRepository->save($city);
        }
        $cityRepository->remove($this->cityMocks['taipei']);

        $taipeiClosesCity = $cityRepository->getClosestCity($this->cityMocks['taipei']);
        self::assertEquals('shanghai', $taipeiClosesCity->getName()->value());
    }


}
