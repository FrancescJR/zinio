<?php
declare(strict_types=1);

namespace Zinio\Cesc\Application\City\PlainObject;

use Zinio\Cesc\Domain\City\City;

class CityPO
{
    public $name;

    public function __construct(City $city)
    {
        $this->name = $city->getName()->value();
    }

    public function __toString(): string
    {
        return $this->name."\n";
    }


}
