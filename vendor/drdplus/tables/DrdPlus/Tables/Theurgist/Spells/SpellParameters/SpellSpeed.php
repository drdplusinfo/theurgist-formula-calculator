<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

/**
 * @method SpellSpeed getWithAddition($additionValue)
 */
class SpellSpeed extends CastingParameter
{
    public function getSpeedBonus(): SpeedBonus
    {
        return SpeedBonus::getIt($this->getValue(), $this->getTables());
    }
}