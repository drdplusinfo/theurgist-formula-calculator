<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Weight;

use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementFileTable;
use DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger;
use DrdPlus\Tables\Measurements\Tools\DummyEvaluator;
use DrdPlus\Calculations\SumAndRound;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;

/**
 * See PPH page 164 bottom, @link https://pph.drdplus.info/#tabulka_hmotnosti
 */
class WeightTable extends AbstractMeasurementFileTable
{
    public function __construct()
    {
        parent::__construct(new DummyEvaluator()); // no dice roll is expected
    }

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/weight.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeader(): array
    {
        return [Weight::KG];
    }

    /**
     * @param WeightBonus $weightBonus
     * @return Weight|MeasurementWithBonus
     */
    public function toWeight(WeightBonus $weightBonus)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->toMeasurement($weightBonus, Weight::KG);
    }

    public function toBonus(Weight $weight)
    {
        return $this->measurementToBonus($weight);
    }

    /**
     * @param int $bonusValue
     * @return WeightBonus|AbstractBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    protected function createBonus(int $bonusValue): AbstractBonus
    {
        return new WeightBonus($bonusValue, $this);
    }

    /**
     * @param float $value
     * @param string $unit
     * @return Weight|MeasurementWithBonus
     */
    protected function convertToMeasurement(float $value, string $unit): MeasurementWithBonus
    {
        return new Weight($value, Weight::KG, $this);
    }

    /**
     * @param int|IntegerInterface $simplifiedBonus
     * @return WeightBonus
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function getBonusFromSimplifiedBonus($simplifiedBonus): WeightBonus
    {
        try {
            return $this->createBonus(ToInteger::toInteger($simplifiedBonus) + 12);
        } catch (\Granam\Integer\Tools\Exceptions\WrongParameterType $exception) {
            throw new BonusRequiresInteger($exception->getMessage());
        }
    }

    /**
     * Affects activities using strength, agility or knack, see PPH page 113, right column, bottom.
     *
     * @param Strength $strength
     * @param Weight $cargoWeight
     * @return int negative number or zero
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function getMalusFromLoad(Strength $strength, Weight $cargoWeight): int
    {
        $requiredStrength = $cargoWeight->getBonus()->getValue();
        $missingStrength = $requiredStrength - $strength->getValue();
        $malus = -SumAndRound::half($missingStrength); // see PPH page 113, right column
        if ($malus > 0) {
            return 0;
        }

        return $malus;
    }

}