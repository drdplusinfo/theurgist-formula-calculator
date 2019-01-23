<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials;

use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Number\NumberInterface;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

abstract class CastingParameter extends StrictObject implements IntegerInterface
{
    use GetParameterNameTrait;

    /**
     * @var int
     */
    private $defaultValue;
    /**
     * @var AdditionByDifficulty
     */
    private $additionByDifficulty;

    /**
     * @param array $values 0 => default value and 1 => addition by difficulty notation, 2 => current addition / null
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForCastingParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfDifficultyIncrement
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyValue
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatOfAdditionByDifficultyNotation
     */
    public function __construct(array $values)
    {
        try {
            $this->defaultValue = ToInteger::toInteger($values[0] ?? null);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidValueForCastingParameter(
                "Expected integer for {$this->getParameterName()}, got "
                . (array_key_exists(0, $values) ? ValueDescriber::describe($values[0], true) : 'nothing')
            );
        }
        if (!array_key_exists(1, $values)) {
            throw new Exceptions\MissingValueForFormulaDifficultyAddition(
                'Missing index 1 for addition by realm in given values ' . var_export($values, true)
                . ' for ' . $this->getParameterName()
            );
        }
        $this->additionByDifficulty = new AdditionByDifficulty($values[1], $values[2] ?? null);
    }

    /**
     * @return int
     */
    public function getDefaultValue(): int
    {
        return $this->defaultValue;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->getDefaultValue() + $this->getAdditionByDifficulty()->getCurrentAddition();
    }

    /**
     * @return AdditionByDifficulty
     */
    public function getAdditionByDifficulty(): AdditionByDifficulty
    {
        return $this->additionByDifficulty;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->getValue()} ({$this->getAdditionByDifficulty()})";
    }

    /**
     * @param int|float|NumberInterface $additionValue
     * @return CastingParameter
     * @throws \Granam\Integer\Tools\Exceptions\Exception
     */
    public function getWithAddition($additionValue): CastingParameter
    {
        $additionValue = ToInteger::toInteger($additionValue);
        if ($additionValue === $this->getAdditionByDifficulty()->getCurrentAddition()) {
            return $this;
        }

        return new static(
            [$this->getDefaultValue(), $this->getAdditionByDifficulty()->getNotation(), $additionValue /* current addition */]
        );
    }
}