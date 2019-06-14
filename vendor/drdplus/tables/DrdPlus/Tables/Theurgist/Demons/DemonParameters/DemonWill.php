<?php declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Will;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

/**
 * @method DemonWill getWithAddition($additionValue)
 */
class DemonWill extends CastingParameter
{
    public function getWill(): Will
    {
        return Will::getIt($this->getValue());
    }
}