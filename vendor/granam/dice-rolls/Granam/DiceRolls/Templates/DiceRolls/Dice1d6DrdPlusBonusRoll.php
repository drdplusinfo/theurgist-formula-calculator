<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\DiceRolls;

use Granam\DiceRolls\Templates\Evaluators\FourOrMoreAsOneZeroOtherwiseEvaluator;
use Granam\Integer\IntegerInterface;

class Dice1d6DrdPlusBonusRoll extends AbstractDice1d6Roll
{

    /**
     * @param IntegerInterface|int $rolledNumber
     * @param IntegerInterface|int $sequenceNumber
     * @throws \Granam\DiceRolls\Templates\DiceRolls\Exceptions\Invalid1d6DiceRollValue
     * @throws \Granam\DiceRolls\Exceptions\InvalidSequenceNumber
     */
    public function __construct($rolledNumber, $sequenceNumber)
    {
        parent::__construct(
            $rolledNumber,
            FourOrMoreAsOneZeroOtherwiseEvaluator::getIt(),
            $sequenceNumber
        );
    }
}