<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements\Time;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementFileTable;
use DrdPlus\Tables\Measurements\Tools\DummyEvaluator;

/**
 * See PPH page 161, @link https://pph.drdplus.info/#tabulka_rychlosti
 */
class TimeTable extends AbstractMeasurementFileTable
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
        return __DIR__ . '/data/time.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeader(): array
    {
        return [TimeUnitCode::ROUND, TimeUnitCode::MINUTE, TimeUnitCode::HOUR, TimeUnitCode::DAY, TimeUnitCode::MONTH, TimeUnitCode::YEAR];
    }

    /**
     * @param TimeBonus $timeBonus
     * @param string|null $wantedUnit
     * @return Time|MeasurementWithBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function toTime(TimeBonus $timeBonus, string $wantedUnit = null): Time
    {
        return $this->toMeasurement($timeBonus, $wantedUnit);
    }

    /**
     * @param TimeBonus $timeBonus
     * @param string|null $wantedUnit = null
     * @return bool
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    public function hasTimeFor(TimeBonus $timeBonus, string $wantedUnit = null): bool
    {
        return $this->hasMeasurementFor($timeBonus, $wantedUnit);
    }

    /**
     * @param Time $time
     * @return TimeBonus|AbstractBonus
     */
    public function toBonus(Time $time): TimeBonus
    {
        return $this->measurementToBonus($time);
    }

    /**
     * @param float $value
     * @param string $unit
     * @return Time|MeasurementWithBonus
     */
    protected function convertToMeasurement(float $value, string $unit): MeasurementWithBonus
    {
        return new Time($value, $unit, $this);
    }

    /**
     * @param int $bonusValue
     * @return TimeBonus|AbstractBonus
     */
    protected function createBonus(int $bonusValue): AbstractBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new TimeBonus($bonusValue, $this);
    }

}