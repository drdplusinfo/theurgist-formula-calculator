<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameter;

/**
 * @method CastingRounds getWithAddition($additionValue)
 */
class CastingRounds extends PositiveCastingParameter
{
    public function getTime(): Time
    {
        return new Time($this->getValue(), Time::ROUND, $this->getTables()->getTimeTable());
    }

    public function getTimeBonus(): TimeBonus
    {
        return $this->getTime()->getBonus();
    }
}