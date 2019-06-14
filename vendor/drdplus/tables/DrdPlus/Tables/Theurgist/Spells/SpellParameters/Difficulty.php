<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use Granam\Integer\PositiveInteger;
use Granam\Integer\Tools\ToInteger;
use Granam\Number\NumberInterface;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class Difficulty extends StrictObject implements PositiveInteger
{
    /**
     * @var int
     */
    private $minimal;
    /**
     * @var int
     */
    private $maximal;
    /**
     * @var DifficultyAddition
     */
    private $difficultyAddition;

    /**
     * @param array $values [ 0 => minimal, 1 => maximal, 2 => difficulty addition per realm, 3 => current difficulty addition value]
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\MinimalDifficultyCanNotBeGreaterThanMaximal
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidValueForMinimalDifficulty
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidValueForMaximalDifficulty
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsNotation
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfRealmsIncrement
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    public function __construct(array $values)
    {
        try {
            $this->minimal = ToInteger::toPositiveInteger($values[0] ?? null);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidValueForMinimalDifficulty(
                'Expected positive integer for minimal difficulty, got '
                . (array_key_exists(0, $values) ? ValueDescriber::describe($values[0]) : 'nothing')
            );
        }
        try {
            $this->maximal = ToInteger::toPositiveInteger($values[1] ?? null);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidValueForMaximalDifficulty(
                'Expected positive integer for maximal difficulty, got '
                . (array_key_exists(1, $values) ? ValueDescriber::describe($values[1]) : 'nothing')
            );
        }
        if ($this->minimal > $this->maximal) {
            throw new Exceptions\MinimalDifficultyCanNotBeGreaterThanMaximal(
                'Minimal difficulty has to be lesser or equal to maximal.'
                . " Got minimum {$this->minimal} and maximum {$this->maximal}"
            );
        }
        if (!array_key_exists(2, $values)) {
            throw new Partials\Exceptions\MissingValueForFormulaDifficultyAddition(
                'Missing index 2 for formula difficulty addition by realm in given values ' . var_export($values, true)
                . ' for difficulty'
            );
        }
        $this->difficultyAddition = new DifficultyAddition($values[2], $values[3] ?? 0 /* current addition value */);
    }

    /**
     * Works as difficulty (some kind of "price") for basic, not changed formula.
     * Can differs from 'value', @return int
     * @see getValue, which is current difficulty of even modified formula.
     *
     */
    public function getMinimal(): int
    {
        return $this->minimal;
    }

    /**
     * Maximal difficulty a formula from lowest possible realm can handle.
     * Can be even LESS than 'value', @return int
     * @see getValue, which is current difficulty of even heavily modified formula.
     *
     */
    public function getMaximal(): int
    {
        return $this->maximal;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->getMinimal() + $this->difficultyAddition->getCurrentAddition();
    }

    /**
     * @return DifficultyAddition
     */
    public function getDifficultyAddition(): DifficultyAddition
    {
        return $this->difficultyAddition;
    }

    /**
     * How much have to be realms increased to manage total difficulty
     *
     * @return int
     */
    public function getCurrentRealmsIncrement(): int
    {
        $currentDifficulty = $this->getValue();
        $maximalDifficulty = $this->getMaximal();
        if ($currentDifficulty <= $maximalDifficulty) {
            return 0;
        }
        $additionalDifficulty = $currentDifficulty - $maximalDifficulty;
        $steps = $additionalDifficulty / $this->getDifficultyAddition()->getDifficultyAdditionPerStep();
        $realmsIncrement = $steps * $this->getDifficultyAddition()->getRealmsChangePerAdditionStep();

        return (int)\ceil($realmsIncrement); // even a tiny piece of a higher realm means the lower realm is not able to create that formula
    }

    /**
     * @param int|float|NumberInterface $difficultyChangeValue
     * @return Difficulty
     */
    public function getWithDifficultyChange($difficultyChangeValue): Difficulty
    {
        $difficultyChangeValue = ToInteger::toInteger($difficultyChangeValue);
        if ($difficultyChangeValue === 0) {
            return $this; // no change needed
        }

        return new static(
            [
                $this->getMinimal(),
                $this->getMaximal(),
                $this->getDifficultyAddition()->getNotation(),
                $difficultyChangeValue,
            ]
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $asString = (string)$this->getValue();
        $asString .= ' (' . $this->getMinimal() . '...' . $this->getMaximal();
        $asString .= ' [' . $this->getDifficultyAddition() . ']';
        $asString .= ')';

        return $asString;
    }
}