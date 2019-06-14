<?php
namespace DrdPlus\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Knack;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

/**
 * @method DemonKnack getWithAddition($additionValue)
 */
class DemonKnack extends CastingParameter
{
    public function getKnack(): Knack
    {
        return Knack::getIt($this->getValue());
    }
}