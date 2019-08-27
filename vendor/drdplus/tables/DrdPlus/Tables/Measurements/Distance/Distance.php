<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Distance;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Exceptions\UnknownUnit;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementWithBonus;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

class Distance extends AbstractMeasurementWithBonus
{
    public const DECIMETER = DistanceUnitCode::DECIMETER;
    public const METER = DistanceUnitCode::METER;
    public const KILOMETER = DistanceUnitCode::KILOMETER;
    public const LIGHT_YEAR = DistanceUnitCode::LIGHT_YEAR;

    /**
     * @var DistanceTable
     */
    private $distanceTable;

    /**
     * @param float $value
     * @param string|StringInterface $unit
     * @param DistanceTable $distanceTable
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \Granam\Float\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Float\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function __construct($value, $unit, DistanceTable $distanceTable)
    {
        $this->distanceTable = $distanceTable;
        parent::__construct($value, $unit);
    }

    /**
     * @return string[]
     */
    public function getPossibleUnits(): array
    {
        return [self::DECIMETER, self::METER, self::KILOMETER, self::LIGHT_YEAR];
    }

    /**
     * @return DistanceBonus
     */
    public function getBonus(): DistanceBonus
    {
        return $this->distanceTable->toBonus($this);
    }

    /**
     * @return float
     */
    public function getDecimeters(): float
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValueInDifferentUnit(self::DECIMETER);
    }

    /**
     * @return float
     */
    public function getMeters(): float
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValueInDifferentUnit(self::METER);
    }

    /**
     * @param string|StringInterface $wantedUnit
     * @return Distance
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \Granam\Float\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Float\Tools\Exceptions\ValueLostOnCast
     */
    public function getInUnit($wantedUnit): Distance
    {
        return new Distance($this->getValueInDifferentUnit((string)$wantedUnit), $wantedUnit, $this->distanceTable);
    }

    /**
     * @param string $toUnit
     * @return float
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \Granam\Float\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Float\Tools\Exceptions\ValueLostOnCast
     */
    private function getValueInDifferentUnit(string $toUnit): float
    {
        if ($this->getUnit() === $toUnit) {
            return $this->getValue();
        }
        switch ($this->getUnit()) {
            case self::DECIMETER :
                if ($toUnit === self::METER) {
                    return $this->getValue() / 10;
                }
                if ($toUnit === self::KILOMETER) {
                    return $this->getValue() / 10000;
                }
                break;
            case self::METER :
                if ($toUnit === self::KILOMETER) {
                    return $this->getValue() / 1000;
                }
                if ($toUnit === self::DECIMETER) {
                    return $this->getValue() * 10;
                }
                break;
            case self::KILOMETER :
                if ($toUnit === self::METER) {
                    return $this->getValue() * 1000;
                }
                if ($toUnit === self::DECIMETER) {
                    return $this->getValue() * 10000;
                }
                break;
        }
        throw new UnknownUnit(
            'Unknown one or both from ' . ValueDescriber::describe($this->getUnit())
            . ' to ' . ValueDescriber::describe($toUnit) . ' unit(s)'
        );
    }

    /**
     * @return float
     */
    public function getKilometers(): float
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValueInDifferentUnit(self::KILOMETER);
    }

    /**
     * @return float
     */
    public function getLightYears(): float
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValueInDifferentUnit(self::LIGHT_YEAR);
    }

    /**
     * @return DistanceUnitCode
     */
    public function getUnitCode(): DistanceUnitCode
    {
        return DistanceUnitCode::getIt($this->getUnit());
    }
}