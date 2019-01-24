<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

/**
 * @method Radius getWithAddition($additionValue)
 */
class Radius extends CastingParameter
{
    /**
     * @param DistanceTable $distanceTable
     * @return Distance
     */
    public function getDistance(DistanceTable $distanceTable): Distance
    {
        return (new DistanceBonus($this->getValue(), $distanceTable))->getDistance();
    }
}