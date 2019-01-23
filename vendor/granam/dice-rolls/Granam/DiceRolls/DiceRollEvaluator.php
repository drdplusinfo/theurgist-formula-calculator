<?php
declare(strict_types=1);

namespace Granam\DiceRolls;

use Granam\Integer\IntegerInterface;

interface DiceRollEvaluator
{

    /**
     * @param DiceRoll $diceRoll
     * @return IntegerInterface
     */
    public function evaluateDiceRoll(DiceRoll $diceRoll): IntegerInterface;
}