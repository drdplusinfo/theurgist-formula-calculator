<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Fatigue;

use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;

class FatigueBonus extends AbstractBonus
{
    /**
     * @param $bonusValue
     * @param Tables $tables
     * @return FatigueBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public static function getIt($bonusValue, Tables $tables): FatigueBonus
    {
        return new static($bonusValue, $tables->getFatigueTable());
    }

    /**
     * @var FatigueTable
     */
    private $fatigueTable;

    /**
     * @param int|IntegerInterface $value
     * @param FatigueTable $fatigueTable
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function __construct($value, FatigueTable $fatigueTable)
    {
        parent::__construct($value);
        $this->fatigueTable = $fatigueTable;
    }

    /**
     * @return Fatigue
     */
    public function getFatigue(): Fatigue
    {
        return $this->fatigueTable->toFatigue($this);
    }
}