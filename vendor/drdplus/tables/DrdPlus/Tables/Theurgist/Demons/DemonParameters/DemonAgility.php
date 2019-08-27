<?php declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Agility;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

/**
 * @method DemonAgility getWithAddition($additionValue)
 */
class DemonAgility extends CastingParameter
{
    public function getAgility(): Agility
    {
        return Agility::getIt($this->getValue());
    }
}