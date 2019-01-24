<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Evaluators;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\Templates\Evaluators\FourOrMoreAsOneZeroOtherwiseEvaluator;

class FourOrMoreAsOneZeroOtherwiseEvaluatorTest extends AbstractEvaluatorTest
{
    /**
     * @test
     */
    public function Greater_than_three_is_considered_as_one_otherwise_zero()
    {
        $evaluator = new FourOrMoreAsOneZeroOtherwiseEvaluator();
        /** @var DiceRoll|\Mockery\MockInterface $diceRoll */
        foreach (range(-4, 10, 1) as $value) {
            $evaluated = $evaluator->evaluateDiceRoll($this->createDiceRoll($value));
            if ($value > 3) {
                self::assertSame(
                    1,
                    $evaluated->getValue(),
                    "Value of $value should be 1, but was evaluated as {$evaluated->getValue()}"
                );
            } else {
                self::assertSame(0, $evaluated->getValue());
            }
        }
    }

}