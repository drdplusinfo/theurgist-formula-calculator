<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Distance;

use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;
use Granam\String\StringInterface;

class DistanceBonus extends AbstractBonus
{

    /**
     * @param int|IntegerInterface $value
     * @param Tables $tables
     * @return DistanceBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public static function getIt($value, Tables $tables): DistanceBonus
    {
        return new static($value, $tables->getDistanceTable());
    }

    /** @var DistanceTable */
    private $distanceTable;

    /**
     * @param int|IntegerInterface $value
     * @param DistanceTable $distanceTable
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function __construct($value, DistanceTable $distanceTable)
    {
        parent::__construct($value);
        $this->distanceTable = $distanceTable;
    }

    /**
     * @param string|StringInterface $wantedUnit = null
     * @return Distance
     */
    public function getDistance($wantedUnit = null): Distance
    {
        $distance = $this->distanceTable->toDistance($this);
        if ($wantedUnit === null) {
            return $distance;
        }

        return $distance->getInUnit($wantedUnit);
    }

}