<?php
declare(strict_types=1);

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
     * @param WoundsBonus $woundsBonus
     * @return Wounds|MeasurementWithBonus
     */
    public function toWounds(WoundsBonus $woundsBonus): Wounds
    {
        if ($woundsBonus->getValue() < -21) {
            return new Wounds(0, $this);
        }
        try {
            return $this->toMeasurement($woundsBonus);
        } catch (\DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus $unknownBonus) {
            $woundsValue = \round(10 ** ($woundsBonus->getValue() / 20 + 0.5));
            return $this->convertToMeasurement($woundsValue, Wounds::WOUNDS);
        }
    }

    /**
     * @param Wounds $wounds
     * @return WoundsBonus|AbstractBonus
     */
    public function toBonus(Wounds $wounds): WoundsBonus
    {
        try {
            return $this->measurementToBonus($wounds);
        } catch (\DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange $requestedDataOutOfTableRange) {
            if ($wounds->getValue() < 0) {
                throw $requestedDataOutOfTableRange;
            }
            $bonusValue = (int)\round(20 * \log($wounds->getValue(), 10) - 10);
            return $this->createBonus($bonusValue);
        }
    }

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
        return new Wounds($value, $this);
    }

}