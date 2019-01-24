<?php
declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameter;

/**
 * @method CastingRounds getWithAddition($additionValue)
 */
class CastingRounds extends PositiveCastingParameter
{
    /**
     * @param array $values
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForPositiveCastingParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfDifficultyIncrement
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation
     */
    public function __construct(array $values)
    {
        if (!array_key_exists(1, $values)) {
            $values[1] = 0; // no addition by realms
        }
        parent::__construct($values);
    }

    public function getTime(TimeTable $timeTable): Time
    {
        return new Time($this->getValue(), TimeUnitCode::ROUND, $timeTable);
    }
}