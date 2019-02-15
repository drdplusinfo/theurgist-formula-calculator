<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Body\Healing;

use DrdPlus\Tables\Partials\Exceptions\UnexpectedPercents;
use DrdPlus\Tables\Partials\Percents;
use Granam\Integer\PositiveInteger;

class HealingConditionsPercents extends Percents
{
    /**
     * @param int|PositiveInteger $value
     * @throws \DrdPlus\Tables\Body\Healing\Exceptions\UnexpectedHealingConditionsPercents
     */
    public function __construct($value)
    {
        try {
            parent::__construct($value);
        } catch (UnexpectedPercents $unexpectedPercents) {
            throw new Exceptions\UnexpectedHealingConditionsPercents($unexpectedPercents->getMessage());
        }
    }

}