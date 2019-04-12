<?php
namespace DrdPlus\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Agility;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

class DemonAgility extends CastingParameter
{
    public function getAgility(): Agility
    {
        return Agility::getIt($this->getValue());
    }
}