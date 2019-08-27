<?php declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

/**
 * @method DemonStrength getWithAddition($additionValue)
 */
class DemonStrength extends CastingParameter
{
    public function getStrength(): Strength
    {
        return Strength::getIt($this->getValue());
    }
}