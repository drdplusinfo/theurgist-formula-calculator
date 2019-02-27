<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Wounds;

use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;

class WoundsBonus extends AbstractBonus
{
    /**
     * @param int|IntegerInterface $bonusValue
     * @param Tables $tables
     * @return WoundsBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public static function getIt($bonusValue, Tables $tables): WoundsBonus
    {
        return new static($bonusValue, $tables->getWoundsTable());
    }

    /**
     * @var WoundsTable
     */
    private $woundsTable;

    /**
     * @param int|IntegerInterface $value
     * @param WoundsTable $woundsTable
     */
    public function __construct($value, WoundsTable $woundsTable)
    {
        $this->woundsTable = $woundsTable;
        parent::__construct($value);
    }

    /**
     * @return Wounds
     */
    public function getWounds(): Wounds
    {
        return $this->woundsTable->toWounds($this);
    }
}