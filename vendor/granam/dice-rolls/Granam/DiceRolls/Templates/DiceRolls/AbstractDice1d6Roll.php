<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\DiceRolls;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\DiceRollEvaluator;
use Granam\DiceRolls\Exceptions\InvalidSequenceNumber;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\Integer\IntegerInterface;
use Granam\Integer\IntegerObject;
use Granam\Integer\PositiveIntegerObject;
use Granam\Integer\Tools\ToInteger;

abstract class AbstractDice1d6Roll extends DiceRoll
{

    /**
     * @param IntegerInterface|int $rolledNumber
     * @param DiceRollEvaluator $diceRollEvaluator
     * @param IntegerInterface|int $sequenceNumber
     * @throws \Granam\DiceRolls\Templates\DiceRolls\Exceptions\Invalid1d6DiceRollValue
     * @throws \Granam\DiceRolls\Exceptions\InvalidSequenceNumber
     */
    public function __construct($rolledNumber, DiceRollEvaluator $diceRollEvaluator, $sequenceNumber = 1)
    {
        $rolledNumber = ToInteger::toPositiveInteger($rolledNumber);
        if ($rolledNumber < 1 || $rolledNumber > 6) {
            throw new Exceptions\Invalid1d6DiceRollValue("Expected value in range 1..6, got {$rolledNumber}");
        }
        $sequenceNumber = ToInteger::toPositiveInteger($sequenceNumber);
        if ($sequenceNumber < 1) {
            throw new InvalidSequenceNumber("Sequence number has to be greater than zero, got {$sequenceNumber}");
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct(
            Dice1d6::getIt(),
            new PositiveIntegerObject($rolledNumber),
            new PositiveIntegerObject($sequenceNumber),
            $diceRollEvaluator
        );
    }
}