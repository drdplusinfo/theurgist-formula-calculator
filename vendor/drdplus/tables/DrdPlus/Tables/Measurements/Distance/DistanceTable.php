<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Distance;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementFileTable;
use DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange;
use DrdPlus\Tables\Measurements\Tools\DummyEvaluator;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\String\StringInterface;

/**
 * See PPH page 162 top, @link https://pph.drdplus.info/#tabulka_vzdalenosti
 */
class DistanceTable extends AbstractMeasurementFileTable
{
    public function __construct()
    {
        parent::__construct(new DummyEvaluator());
    }

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/distance.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeader(): array
    {
        return [
            DistanceUnitCode::DECIMETER,
            DistanceUnitCode::METER,
            DistanceUnitCode::KILOMETER,
            DistanceUnitCode::LIGHT_YEAR,
        ];
    }

    /**
     * @param DistanceBonus $distanceBonus
     * @param string|StringInterface $wantedUnit = null
     * @return Distance|MeasurementWithBonus
     */
    public function toDistance(DistanceBonus $distanceBonus, $wantedUnit = null)
    {
        return $this->toMeasurement($distanceBonus, $wantedUnit);
    }

    /**
     * @param Distance $distance
     * @return DistanceBonus|AbstractBonus
     */
    public function toBonus(Distance $distance)
    {
        try {
            return $this->measurementToBonus($distance);
        } catch (RequestedDataOutOfTableRange $requestedDataOutOfTableRange) {
            if ($distance->getUnitCode()->getValue() === Distance::METER) {
                throw $requestedDataOutOfTableRange;
            }
            return $this->measurementToBonus($distance->getInUnit(Distance::METER));
        }
    }

    /**
     * @param float $value
     * @param string $unit
     * @return Distance|MeasurementWithBonus
     */
    protected function convertToMeasurement(float $value, string $unit): MeasurementWithBonus
    {
        return new Distance($value, $unit, $this);
    }

    /**
     * @param int $bonusValue
     * @return DistanceBonus|AbstractBonus
     */
    protected function createBonus(int $bonusValue): AbstractBonus
    {
        return new DistanceBonus($bonusValue, $this);
    }

    /**
     * @param IntegerInterface|int $size
     * @return DistanceBonus
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function sizeToDistanceBonus($size): DistanceBonus
    {
        return $this->createBonus(ToInteger::toInteger($size) + 12);
    }
}