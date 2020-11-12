<?php
declare(strict_types=1);

namespace Domain\City\ValueObject;


use PHPUnit\Framework\TestCase;
use Zinio\Cesc\Domain\City\Exception\InvalidValueException;
use Zinio\Cesc\Domain\City\ValueObject\CityCoordinates;

class CityCoordinatesTest extends TestCase
{

    private const GOOD_LATITUDE = 30.23;
    private const BAD_LATITUDE = 100.23;
    private const GOOD_LONGITUDE = -100.23;
    private const BAD_LONGITUDE =  -200.43;

    public function testBadLatitude():void
    {
        self::expectException(InvalidValueException::class);
        new CityCoordinates(self::BAD_LATITUDE, self::GOOD_LONGITUDE);
    }

    public function testBadLongitude():void
    {
        self::expectException(InvalidValueException::class);
        new CityCoordinates(self::GOOD_LATITUDE, self::BAD_LONGITUDE);
    }

    public function testGetters():void
    {
        $coordinates = new CityCoordinates(self::GOOD_LATITUDE, self::GOOD_LONGITUDE);
        self::assertEquals(self::GOOD_LATITUDE, $coordinates->getLatitude());
        self::assertEquals(self::GOOD_LONGITUDE, $coordinates->getLongitude());
    }

}
