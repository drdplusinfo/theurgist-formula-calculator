<?php declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameter;

/**
 * @method DemonActivationDuration getWithAddition($additionValue)
 */
class DemonActivationDuration extends PositiveCastingParameter
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