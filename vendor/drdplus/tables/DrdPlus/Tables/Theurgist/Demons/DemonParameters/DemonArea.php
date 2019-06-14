<?php declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameter;

/**
 * @method DemonArea getWithAddition($additionValue)
 */
class DemonArea extends PositiveCastingParameter
{
    public function getDistanceBonus(): DistanceBonus
    {
        return DistanceBonus::getIt($this->getValue(), $this->getTables());
    }
}