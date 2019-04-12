<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameter;

/**
 * @method CastingRounds getWithAddition($additionValue)
 */
class CastingRounds extends PositiveCastingParameter
{
    public function getTimeBonus(): TimeBonus
    {
        return TimeBonus::getIt($this->getValue(), $this->getTables());
    }
}