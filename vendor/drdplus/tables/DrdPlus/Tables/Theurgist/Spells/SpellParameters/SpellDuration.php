<?php declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameter;

/**
 * @method SpellDuration getWithAddition($additionValue)
 */
class SpellDuration extends PositiveCastingParameter
{
    /**
     * @return TimeBonus
     * @throws \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertThatBonusToTime
     */
    public function getDurationTimeBonus(): TimeBonus
    {
        return TimeBonus::getIt($this->getValue(), $this->getTables());
    }

}