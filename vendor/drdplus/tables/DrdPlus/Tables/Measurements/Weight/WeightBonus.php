<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Weight;

use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;

class WeightBonus extends AbstractBonus
{
    /**
     * @param int|IntegerInterface $bonusValue
     * @param Tables $tables
     * @return WeightBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public static function getIt($bonusValue, Tables $tables): WeightBonus
    {
        return new static($bonusValue, $tables->getWeightTable());
    }

    /**
     * @var WeightTable
     */
    private $weightTable;

    /**
     * @param int|IntegerInterface $value
     * @param WeightTable $weightTable
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function __construct($value, WeightTable $weightTable)
    {
        $this->weightTable = $weightTable;
        parent::__construct($value);
    }

    /**
     * @return Weight
     */
    public function getWeight(): Weight
    {
        return $this->weightTable->toWeight($this);
    }

}