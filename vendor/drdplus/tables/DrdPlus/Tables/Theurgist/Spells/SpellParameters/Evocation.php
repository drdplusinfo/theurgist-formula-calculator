<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameter;

/**
 * @method Evocation getWithAddition($additionValue)
 */
class Evocation extends PositiveCastingParameter
{
    public function getEvocationTimeBonus(): TimeBonus
    {
        return TimeBonus::getIt($this->getValue(), $this->getTables());
    }

}