<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Speed;

use DrdPlus\Codes\Units\SpeedUnitCode;
use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementFileTable;
use DrdPlus\Tables\Measurements\Tools\DummyEvaluator;

/**
 * See PPH page 163, @link https://pph.drdplus.info/#tabulka_rychlosti
 */
class SpeedTable extends AbstractMeasurementFileTable
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
        return __DIR__ . '/data/speed.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeader(): array
    {
        return [SpeedUnitCode::METER_PER_ROUND, SpeedUnitCode::KILOMETER_PER_HOUR];
    }

    /**
     * @param int $bonusValue
     * @return SpeedBonus|AbstractBonus
     */
    protected function createBonus(int $bonusValue): AbstractBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new SpeedBonus($bonusValue, $this);
    }

    /**
     * @param SpeedBonus $bonus
     * @param string|null $wantedUnit
     * @return Speed|MeasurementWithBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function toSpeed(SpeedBonus $bonus, string $wantedUnit = null)
    {
        return $this->toMeasurement($bonus, $wantedUnit);
    }

    /**
     * @param Speed $speed
     * @return SpeedBonus|AbstractBonus
     */
    public function toBonus(Speed $speed): SpeedBonus
    {
        return $this->measurementToBonus($speed);
    }

    /**
     * @param float $value
     * @param string $unit
     * @return Speed|MeasurementWithBonus
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    protected function convertToMeasurement(float $value, string $unit): MeasurementWithBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Speed($value, $unit, $this);
    }
}