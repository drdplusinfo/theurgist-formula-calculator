<?php declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials;

use DrdPlus\Tables\Tables;
use Granam\Integer\PositiveInteger;
use Granam\Integer\Tools\ToInteger;
use Granam\Tools\ValueDescriber;

abstract class PositiveCastingParameter extends CastingParameter implements PositiveInteger
{
    /**
     * @param array|string[] $values
     * @param Tables $tables
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForPositiveCastingParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfDifficultyIncrement
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation
     */
    public function __construct(array $values, Tables $tables)
    {
        try {
            $values[0] = ToInteger::toPositiveInteger($values[0] ?? null);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidValueForPositiveCastingParameter(
                "Expected positive integer for {$this->getParameterName()}, got "
                . (array_key_exists(0, $values) ? ValueDescriber::describe($values[0]) : 'nothing')
            );
        }
        parent::__construct($values, $tables);
    }
}