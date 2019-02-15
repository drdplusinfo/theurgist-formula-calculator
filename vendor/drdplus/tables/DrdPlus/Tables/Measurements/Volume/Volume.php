<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Volume;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Codes\Units\VolumeUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Exceptions\UnknownUnit;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementWithBonus;
use Granam\Tools\ValueDescriber;

class Volume extends AbstractMeasurementWithBonus
{
    public const LITER = VolumeUnitCode::LITER;
    public const CUBIC_METER = VolumeUnitCode::CUBIC_METER;
    public const CUBIC_KILOMETER = VolumeUnitCode::CUBIC_KILOMETER;

    /** @var DistanceTable */
    private $distanceTable;

    /**
     * @param float $value
     * @param string $unit
     * @param DistanceTable $distanceTable
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function __construct(float $value, string $unit, DistanceTable $distanceTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct($value, $unit);
        $this->distanceTable = $distanceTable;
    }

    public function getPossibleUnits(): array
    {
        return [self::LITER, self::CUBIC_METER, self::CUBIC_KILOMETER];
    }

    public function getBonus(): VolumeBonus
    {
        // B = B(width) + B(height) + B(dept)
        $volumeValue = $this->getValue();
        $cubeSide = $volumeValue ** (1 / 3); // cube root
        $cubeSideDistance = new Distance($cubeSide, $this->getDistanceUnitByVolumeUnit($this->getUnit()), $this->distanceTable);
        $volumeBonusValue = $cubeSideDistance->getBonus()->getValue() * 3;

        return new VolumeBonus($volumeBonusValue, $this->distanceTable);
    }

    /**
     * @param string $volumeUnit
     * @return string
     * @throws \DrdPlus\Tables\Measurements\Volume\Exceptions\UnknownVolumeUnit
     */
    private function getDistanceUnitByVolumeUnit(string $volumeUnit): string
    {
        switch ($volumeUnit) {
            case VolumeUnitCode::LITER :
                return DistanceUnitCode::DECIMETER;
            case VolumeUnitCode::CUBIC_METER :
                return DistanceUnitCode::METER;
            case VolumeUnitCode::CUBIC_KILOMETER :
                return DistanceUnitCode::KILOMETER;
            default :
                throw new Exceptions\UnknownVolumeUnit(
                    "Do not know how to get distance unit of a cube side by cube unit {$volumeUnit}"
                );
        }
    }

    public function getUnitCode(): VolumeUnitCode
    {
        return VolumeUnitCode::getIt($this->getUnit());
    }

    /**
     * @return float
     */
    public function getLiters(): float
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValueInDifferentUnit(self::LITER);
    }

    /**
     * @param string $toUnit
     * @return float
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    private function getValueInDifferentUnit(string $toUnit): float
    {
        if ($this->getUnit() === $toUnit) {
            return $this->getValue();
        }
        switch ($this->getUnit()) {
            case self::LITER :
                if ($toUnit === self::CUBIC_METER) {
                    return $this->getValue() / (10 ** 3);
                }
                if ($toUnit === self::CUBIC_KILOMETER) {
                    return $this->getValue() / ((10 * 1000) ** 3);
                }
                break;
            case self::CUBIC_METER :
                if ($toUnit === self::CUBIC_KILOMETER) {
                    return $this->getValue() / (1000 ** 3);
                }
                if ($toUnit === self::LITER) {
                    return $this->getValue() * (10 ** 3);
                }
                break;
            case self::CUBIC_KILOMETER :
                if ($toUnit === self::CUBIC_METER) {
                    return $this->getValue() * (1000 ** 3);
                }
                if ($toUnit === self::LITER) {
                    return $this->getValue() * ((10 * 1000) ** 3);
                }
                break;
        }
        throw new UnknownUnit(
            'Unknown one or both from ' . ValueDescriber::describe($this->getUnit())
            . ' to ' . ValueDescriber::describe($toUnit) . ' unit(s)'
        );
    }

    public function getCubicMeters(): float
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValueInDifferentUnit(self::CUBIC_METER);
    }

    public function getCubicKilometers(): float
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValueInDifferentUnit(self::CUBIC_KILOMETER);
    }
}