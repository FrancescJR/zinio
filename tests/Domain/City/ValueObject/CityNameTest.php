<?php
declare(strict_types=1);

namespace Domain\City\ValueObject;


use PHPUnit\Framework\TestCase;
use Zinio\Cesc\Domain\City\ValueObject\CityName;

class CityNameTest extends TestCase
{
    private const TEST_NAME = 'test';

    public function testGetter(): void
    {
        $cityName = new CityName(self::TEST_NAME);
        self::assertEquals(self::TEST_NAME, $cityName->value());
    }

}
