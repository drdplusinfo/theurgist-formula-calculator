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

class DifficultyAddition extends StrictObject implements IntegerInterface
{
    /**
     * @var int
     */
    private $realmsChangePerAdditionStep;
    /**
     * @var int
     */
    private $difficultyAdditionPerStep;
    /**
     * @var int
     */
    private $currentAddition;

    /**
     * @param string|int|StringInterface|IntegerInterface $difficultyAdditionByRealmsNotation in format 'difficulty per realm' or 'realms=difficulty per realms'
     * @param int|null $currentAddition How much is currently active addition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsNotation
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfRealmsIncrement
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    public function __construct($difficultyAdditionByRealmsNotation, int $currentAddition = 0/* no addition, no realm increment */)
    {
        $parts = $this->parseParts(ToString::toString($difficultyAdditionByRealmsNotation));
        if (\count($parts) === 1 && \array_keys($parts) === [0]) {
            $this->realmsChangePerAdditionStep = 1;
            $this->difficultyAdditionPerStep = $this->sanitizeAddition($parts[0]);
        } elseif (\count($parts) === 2 && \array_keys($parts) === [0, 1]) {
            $this->realmsChangePerAdditionStep = $this->sanitizeRealms($parts[0]);
            $this->difficultyAdditionPerStep = $this->sanitizeAddition($parts[1]);
        } else {
            throw new Exceptions\InvalidFormatOfAdditionByRealmsNotation(
                "Expected addition by realms in format 'number' or 'number=number', got "
                . ValueDescriber::describe($difficultyAdditionByRealmsNotation)
            );
        }
        $this->currentAddition = $currentAddition;
    }

    /**
     * @param string $additionByRealmNotation
     * @return array|string[]
     */
    private function parseParts(string $additionByRealmNotation): array
    {
        $parts = \array_map(
            function (string $part) {
                return \trim($part);
            },
            \explode('=', $additionByRealmNotation)
        );

        foreach ($parts as $part) {
            if ($part === '') {
                return [];
            }
        }

        return $parts;
    }

    /**
     * @param $realmIncrement
     * @return int
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfRealmsIncrement
     */
    private function sanitizeRealms($realmIncrement): int
    {
        try {
            return ToInteger::toPositiveInteger($realmIncrement);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidFormatOfRealmsIncrement(
                'Expected positive integer for realms increment , got ' . ValueDescriber::describe($realmIncrement)
            );
        }
    }

    /**
     * @param $addition
     * @return int
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    private function sanitizeAddition($addition): int
    {
        try {
            return ToInteger::toInteger($addition);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidFormatOfAdditionByRealmsValue(
                'Expected integer for addition by realm, got ' . ValueDescriber::describe($addition)
            );
        }
    }

    /**
     * How is realms increased on addition step, @return int
     * @see getDifficultyAdditionPerStep.
     *
     */
    public function getRealmsChangePerAdditionStep(): int
    {
        return $this->realmsChangePerAdditionStep;
    }

    /**
     * Bonus given by increasing realms, @return int
     * @see getRealmsChangePerAdditionStep
     *
     */
    public function getDifficultyAdditionPerStep(): int
    {
        return $this->difficultyAdditionPerStep;
    }

    /**
     * Current value of a difficulty "paid" by realms
     *
     * @return int
     */
    public function getCurrentAddition(): int
    {
        return $this->currentAddition;
    }

    /**
     * Same as @return int
     * @see getCurrentAddition (representing current value of an Integer object)
     *
     */
    public function getValue(): int
    {
        return $this->getCurrentAddition();
    }

    /**
     * @param int|float|NumberInterface $value
     * @return DifficultyAddition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    public function add($value): DifficultyAddition
    {
        $value = $this->sanitizeAddition($value);
        if ($value === 0) {
            return $this;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(
            $this->getNotation(),
            $this->getValue() + ToInteger::toInteger($value) // current addition is injected as second parameter
        );
    }

    /**
     * @param int|float|NumberInterface $value
     * @return DifficultyAddition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByRealmsValue
     */
    public function sub($value): DifficultyAddition
    {
        $value = $this->sanitizeAddition($value);
        if ($value === 0) {
            return $this;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
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
        return "{$this->getValue()} {{$this->getRealmsChangePerAdditionStep()}=>{$this->getDifficultyAdditionPerStep()}}";
    }

    /**
     * @return string
     */
    public function getNotation(): string
    {
        return "{$this->getRealmsChangePerAdditionStep()}={$this->getDifficultyAdditionPerStep()}";
    }
}