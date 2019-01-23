<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Dice;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

class CustomDice extends StrictObject implements Dice
{

    /** @var IntegerInterface */
    private $minimum;
    /** @var IntegerInterface */
    private $maximum;

    /**
     * @param IntegerInterface $minimum
     * @param IntegerInterface $maximum
     * @throws \Granam\DiceRolls\Templates\Dices\Exceptions\InvalidDiceRange
     */
    public function __construct(IntegerInterface $minimum, IntegerInterface $maximum)
    {
        $this->checkRange($minimum, $maximum);
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    /**
     * @param IntegerInterface $minimum
     * @param IntegerInterface $maximum
     * @throws \Granam\DiceRolls\Templates\Dices\Exceptions\InvalidDiceRange
     */
    private function checkRange(IntegerInterface $minimum, IntegerInterface $maximum)
    {
        $minimumValue = $minimum->getValue();
        $maximumValue = $maximum->getValue();
        if ($minimumValue > $maximumValue) {
            throw new Exceptions\InvalidDiceRange(
                "Minimum (given {$minimumValue}) can not be higher then maximum (given {$maximumValue})"
            );
        }
        if ($maximumValue < 0 || $maximumValue === 0) {
            throw new Exceptions\InvalidDiceRange("Maximum (given {$maximumValue}) has to be positive integer");
        }
        if ($minimumValue < 0 || $minimumValue === 0) {
            throw new Exceptions\InvalidDiceRange("Minimum (given {$minimumValue}) has to be positive integer");
        }
    }

    /**
     * @return IntegerInterface
     */
    public function getMinimum(): IntegerInterface
    {
        return $this->minimum;
    }

    /**
     * @return IntegerInterface
     */
    public function getMaximum(): IntegerInterface
    {
        return $this->maximum;
    }
}