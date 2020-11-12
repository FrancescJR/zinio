<?php
declare(strict_types=1);

namespace Application\City;


use PHPUnit\Framework\TestCase;
use Zinio\Cesc\Application\City\GetShortestPathService;
use Zinio\Cesc\Application\City\PlainObject\CityPO;
use Zinio\Cesc\Domain\City\City;
use Zinio\Cesc\Domain\City\Service\CreateCitiesService;

class GetShortestPathServiceTest extends TestCase
{

    public function testExecute(): void
    {
        $city = self::createMock(City::class);
        $createCitiesService = self::createMock(CreateCitiesService::class);
        $createCitiesService->method('execute')->willReturn([$city]);

        $service = new GetShortestPathService($createCitiesService);

        $result = $service->execute([]);

        self::assertCount(1, $result);
        self::assertEquals(CityPO::class, get_class($result[0]));
    }

}
