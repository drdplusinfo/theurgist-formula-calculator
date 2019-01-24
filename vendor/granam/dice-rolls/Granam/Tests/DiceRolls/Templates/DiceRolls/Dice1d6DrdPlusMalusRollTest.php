<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\DiceRolls;

use Granam\DiceRolls\DiceRollEvaluator;
use Granam\DiceRolls\Templates\Evaluators\ThreeOrLessAsMinusOneZeroOtherwiseEvaluator;

class Dice1d6DrdPlusMalusRollTest extends AbstractDice1d6RollTest
{
    protected function getDiceRollEvaluator(): DiceRollEvaluator
    {
        return ThreeOrLessAsMinusOneZeroOtherwiseEvaluator::getIt();
    }
}