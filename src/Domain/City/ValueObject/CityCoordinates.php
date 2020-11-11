<?php
declare(strict_types = 1);

namespace Zinio\Cesc\Domain\City\ValueObject;


use Zinio\Cesc\Domain\City\Exception\InvalidValueException;

class CityCoordinates
{
    /**
     * Coordinates constructor.
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @throws InvalidValueException
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->validateLatitude($latitude);
        $this->validateLongitude($longitude);
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $latitude
     *
     * @throws InvalidValueException
     */
    private function validateLatitude(float $latitude)
    {
        if ($latitude < -90 || $latitude > 90) {
            throw new InvalidValueException("The latitude {$latitude} is invalid");
        }
    }

    /**
     * @param float $longitude
     *
     * @throws InvalidValueException
     */
    private function validateLongitude(float $longitude)
    {
        if ($longitude < -180 || $longitude > 180) {
            throw new InvalidValueException("The longitude {$longitude} is invalid");
        }
    }
}
