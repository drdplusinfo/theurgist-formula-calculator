<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements\Partials;

use DrdPlus\Tables\Measurements\Exceptions\UnknownUnit;
use DrdPlus\Tables\Measurements\Measurement;
use Granam\Float\FloatInterface;
use Granam\Float\Tools\ToFloat;
use Granam\Integer\IntegerInterface;
use Granam\Scalar\Tools\ToString;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;
use Granam\Strict\Object\StrictObject;

abstract class AbstractMeasurement extends StrictObject implements Measurement
{

    /** @var float */
    private $value;
    /** @var string */
    private $unit;

    /**
     * @param float|int|FloatInterface|IntegerInterface $value
     * @param string|StringInterface $unit
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \Granam\Float\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Float\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    protected function __construct($value, $unit)
    {
        $this->value = $this->normalizeValue($value);
        $unit = ToString::toString($unit);
        $this->checkUnit($unit);
        $this->unit = $unit;
    }

    /**
     * @param mixed $value
     * @return int|float
     * @throws \Granam\Float\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Float\Tools\Exceptions\ValueLostOnCast
     */
    protected function normalizeValue($value)
    {
        return ToFloat::toFloat($value);
    }

    /**
     * @param string $unit
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    protected function checkUnit(string $unit): void
    {
        if (!\in_array($unit, $this->getPossibleUnits(), true)) {
            throw new UnknownUnit('Unknown unit ' . ValueDescriber::describe($unit));
        }
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @return int|float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue() . ' ' . $this->getUnit();
    }

}