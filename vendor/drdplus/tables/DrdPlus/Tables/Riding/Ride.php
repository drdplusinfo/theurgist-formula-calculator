<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Riding;

use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;
use \Granam\Integer\Tools\Exceptions\Exception as ToIntegerException;

class Ride extends StrictObject implements IntegerInterface
{
    private $rideValue;

    /**
     * @param int $rideValue
     * @throws \DrdPlus\Tables\Riding\Exceptions\InvalidRideValue
     * @throws \Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative
     */
    public function __construct($rideValue)
    {
        try {
            $this->rideValue = ToInteger::toInteger($rideValue);
        } catch (ToIntegerException $toIntegerException) {
            throw new Exceptions\InvalidRideValue($toIntegerException->getMessage());
        }
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->rideValue;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->rideValue;
    }

}