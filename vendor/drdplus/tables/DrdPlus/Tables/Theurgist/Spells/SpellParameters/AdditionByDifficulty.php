<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Number\NumberInterface;
use Granam\Scalar\Tools\ToString;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

/**
 * A bonus paid by difficulty increment
 */
class AdditionByDifficulty extends StrictObject implements IntegerInterface
{
    /** @var int */
    private $difficultyPerAdditionStep;
    /** @var int */
    private $additionStep;
    /** @var int */
    private $currentAddition;

    /**
     * @param string|int|StringInterface|IntegerInterface $additionByDifficultyNotation in format 'number' or 'number=number'
     * @param int|null $currentAddition How much is currently active addition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfDifficultyIncrement
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation
     */
    public function __construct($additionByDifficultyNotation, int $currentAddition = null)
    {
        $parts = $this->parseParts(ToString::toString($additionByDifficultyNotation));
        if (\count($parts) === 1 && \array_keys($parts) === [0]) {
            $this->difficultyPerAdditionStep = 1;
            $this->additionStep = $this->sanitizeAddition($parts[0]);
        } elseif (\count($parts) === 2 && \array_keys($parts) === [0, 1]) {
            $this->difficultyPerAdditionStep = $this->sanitizeDifficulty($parts[0]);
            $this->additionStep = $this->sanitizeAddition($parts[1]);
        } else {
            throw new Exceptions\InvalidFormatOfAdditionByDifficultyNotation(
                "Expected addition by difficulty in format 'number' or 'number=number', got "
                . ValueDescriber::describe($additionByDifficultyNotation)
            );
        }
        $this->currentAddition = $currentAddition ?? 0;/* no addition, no difficulty change */
    }

    /**
     * @param string $additionByDifficultyNotation
     * @return array|string[]
     */
    private function parseParts(string $additionByDifficultyNotation): array
    {
        $parts = \array_map(
            function (string $part) {
                return \trim($part);
            },
            \explode('=', $additionByDifficultyNotation)
        );

        foreach ($parts as $part) {
            if ($part === '') {
                return [];
            }
        }

        return $parts;
    }

    /**
     * @param int|IntegerInterface $difficultyChange
     * @return int
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfDifficultyIncrement
     */
    private function sanitizeDifficulty($difficultyChange): int
    {
        try {
            return ToInteger::toPositiveInteger($difficultyChange);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidFormatOfDifficultyIncrement(
                'Expected positive integer for difficulty increment , got ' . ValueDescriber::describe($difficultyChange)
            );
        }
    }

    /**
     * @param int|IntegerInterface $addition
     * @return int
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     */
    private function sanitizeAddition($addition): int
    {
        try {
            return ToInteger::toInteger($addition);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidFormatOfAdditionByDifficultyValue(
                'Expected integer for addition by difficulty, got ' . ValueDescriber::describe($addition)
            );
        }
    }

    /**
     * How is difficulty increased on addition step, @see getAdditionStep.
     *
     * @return int
     */
    public function getDifficultyPerAdditionStep(): int
    {
        return $this->difficultyPerAdditionStep;
    }

    /**
     * Bonus given by increasing difficulty by a point(s), @see getDifficultyPerAdditionStep
     *
     * @return int
     */
    public function getAdditionStep(): int
    {
        return $this->additionStep;
    }

    /**
     * Current value of a bonus paid by difficulty points, steps x @see getAdditionStep
     *
     * @return int
     */
    public function getCurrentAddition(): int
    {
        return $this->currentAddition;
    }

    /**
     * How much is difficulty increased to get total bonus, steps x @see getCurrentAddition
     *
     * @return int
     */
    public function getCurrentDifficultyIncrement(): int
    {
        if ($this->getAdditionStep() === 0) { // this addition ha no steps, so can not be changed
            return 0;
        }

        return ToInteger::toInteger(\ceil($this->getCurrentAddition() / $this->getAdditionStep() * $this->getDifficultyPerAdditionStep()));
    }

    /**
     * Same as @see getCurrentAddition (representing current value of an Integer object)
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->getCurrentAddition();
    }

    /**
     * @param int|float|NumberInterface $value
     * @return AdditionByDifficulty
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\AdditionByDifficultyWithoutStepsCanNotBeChanged
     */
    public function add($value): AdditionByDifficulty
    {
        $value = $this->sanitizeAddition($value);
        if ($value === 0) {
            return $this;
        }

        if ($this->getAdditionStep() === 0) {
            throw new Exceptions\AdditionByDifficultyWithoutStepsCanNotBeChanged(
                'With zero step can not be an addition changed by ' . ValueDescriber::describe($value)
            );
        }

        return new static(
            $this->getNotation(),
            $this->getValue() + ToInteger::toInteger($value) // current addition is injected as second parameter
        );
    }

    /**
     * @param int|float|NumberInterface $value
     * @return AdditionByDifficulty
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\AdditionByDifficultyWithoutStepsCanNotBeChanged
     */
    public function sub($value): AdditionByDifficulty
    {
        $value = $this->sanitizeAddition($value);
        if ($value === 0) {
            return $this;
        }
        if ($this->getAdditionStep() === 0) {
            throw new Exceptions\AdditionByDifficultyWithoutStepsCanNotBeChanged(
                'With zero step can not be an addition changed by ' . ValueDescriber::describe($value)
            );
        }

        return new static(
            $this->getNotation(),
            $this->getValue() - ToInteger::toInteger($value) // current addition is injected as second parameter
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->getValue()} {{$this->getDifficultyPerAdditionStep()}=>{$this->getAdditionStep()}}";
    }

    /**
     * @return string
     */
    public function getNotation(): string
    {
        return "{$this->getDifficultyPerAdditionStep()}={$this->getAdditionStep()}";
    }
}