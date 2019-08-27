<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Amount;

use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementFileTable;
use DrdPlus\Tables\Measurements\Tools\DiceChanceEvaluator;
use Granam\DiceRolls\Templates\Rollers\Roller1d6;

/**
 * See PPH page 164 top, @link https://pph.drdplus.info/#tabulka_poctu
 */
class AmountTable extends AbstractMeasurementFileTable
{
    public function __construct()
    {
        parent::__construct(new DiceChanceEvaluator(Roller1d6::getIt()));
    }

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/amount.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeader(): array
    {
        return [Amount::AMOUNT];
    }

    /**
     * @param AmountBonus $bonus
     * @return Amount|MeasurementWithBonus
     */
    public function toAmount(AmountBonus $bonus): Amount
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->toMeasurement($bonus);
    }

    /**
     * @param Amount $amount
     * @return AmountBonus|AbstractBonus
     */
    public function toBonus(Amount $amount): AmountBonus
    {
        return $this->measurementToBonus($amount);
    }

    /**
     * @param float $value
     * @param string $unit
     * @return Amount|MeasurementWithBonus
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    protected function convertToMeasurement(float $value, string $unit): MeasurementWithBonus
    {
        $this->checkUnitExistence($unit);

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Amount($value, Amount::AMOUNT, $this);
    }

    /**
     * @param int|$bonusValue
     * @return AmountBonus|AbstractBonus
     */
    protected function createBonus(int $bonusValue): AbstractBonus
    {
        return new AmountBonus($bonusValue, $this);
    }

}