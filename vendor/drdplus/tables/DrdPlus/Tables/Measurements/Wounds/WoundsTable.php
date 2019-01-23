<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements\Wounds;

use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementFileTable;
use DrdPlus\Tables\Measurements\Tools\DiceChanceEvaluator;
use Granam\DiceRolls\Templates\Rollers\Roller1d6;

/**
 * Note: fatigue table is equal to wounds table.
 * See PPH page 165 top, @link https://pph.drdplus.info/#tabulka_zraneni_a_unavy
 * Bonus can be calculated as 20*log(value)-10 and value as 10**(bonus/20 + 0.5),
 * but few values where rounded in the resulting table.
 */
class WoundsTable extends AbstractMeasurementFileTable
{
    public function __construct()
    {
        parent::__construct(new DiceChanceEvaluator(Roller1d6::getIt()));
    }

    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/wounds.csv';
    }

    protected function getExpectedDataHeader(): array
    {
        return [Wounds::WOUNDS];
    }

    /**
     * @param WoundsBonus $bonus
     * @return Wounds|MeasurementWithBonus
     */
    public function toWounds(WoundsBonus $bonus): Wounds
    {
        if ($bonus->getValue() < -21) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return new Wounds(0, $this);
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->toMeasurement($bonus);
    }

    /**
     * @param Wounds $wounds
     * @return WoundsBonus|AbstractBonus
     */
    public function toBonus(Wounds $wounds): WoundsBonus
    {
        return $this->measurementToBonus($wounds);
    }

    /**
     * @param int $bonusValue
     * @return AbstractBonus
     */
    protected function createBonus(int $bonusValue): AbstractBonus
    {
        return new WoundsBonus($bonusValue, $this);
    }

    /**
     * @param float $value
     * @param string $unit
     * @return Wounds|MeasurementWithBonus
     */
    protected function convertToMeasurement(float $value, string $unit): MeasurementWithBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Wounds($value, $this);
    }

}