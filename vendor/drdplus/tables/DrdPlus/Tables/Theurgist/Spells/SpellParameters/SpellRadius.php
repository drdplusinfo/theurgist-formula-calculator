<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;

/**
 * @method SpellRadius getWithAddition($additionValue)
 */
class SpellRadius extends CastingParameter
{
    public function getDistanceBonus(): DistanceBonus
    {
        return DistanceBonus::getIt($this->getValue(), $this->getTables());
    }
}