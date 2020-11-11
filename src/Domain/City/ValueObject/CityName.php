<?php
declare(strict_types=1);

namespace Zinio\Cesc\Domain\City\ValueObject;


class CityName
{
    /**
     * @var string
     */
    private $value;

    /**
     * CityName constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->value = $name;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

}
