<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rolls;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\Roll;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\Integer\PositiveInteger;
use Granam\Tools\ValueDescriber;

class Roll1d6 extends Roll implements PositiveInteger
{
    /**
     * @param DiceRoll $diceRoll
     * @throws Exceptions\UnexpectedDice
     */
    public function __construct(DiceRoll $diceRoll)
    {
        if (!($diceRoll->getDice() instanceof Dice1d6)) {
            throw new Exceptions\UnexpectedDice(
                'Expected roll with dice ' . Dice1d6::class . ', got ' . ValueDescriber::describe($diceRoll)
                . ' with dice ' . ValueDescriber::describe($diceRoll->getDice())
            );
        }
        parent::__construct([$diceRoll]);
    }

    /**
     * @return DiceRoll
     */
    public function getDiceRoll(): DiceRoll
    {
        $standardDiceRolls = $this->getStandardDiceRolls();

        return reset($standardDiceRolls);
    }
}