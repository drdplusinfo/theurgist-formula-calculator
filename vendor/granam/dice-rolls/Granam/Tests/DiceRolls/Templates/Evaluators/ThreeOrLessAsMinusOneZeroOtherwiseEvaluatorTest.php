<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Evaluators;

use Granam\DiceRolls\Templates\Evaluators\ThreeOrLessAsMinusOneZeroOtherwiseEvaluator;

class ThreeOrLessAsMinusOneZeroOtherwiseEvaluatorTest extends AbstractEvaluatorTest
{

    /**
     * @test
     */
    public function Lesser_than_four_value_is_considered_as_minus_one_zero_otherwise()
    {
        $evaluator = ThreeOrLessAsMinusOneZeroOtherwiseEvaluator::getIt();
        foreach (range(-4, 10, 1) as $value) {
            $evaluated = $evaluator->evaluateDiceRoll($this->createDiceRoll($value));
            if ($value < 4) {
                self::assertSame(
                    -1,
                    $evaluated->getValue(),
                    "Value of $value should be -1, but was evaluated as {$evaluated->getValue()}"
                );
            } else {
                self::assertSame(0, $evaluated->getValue());
            }
        }
        self::assertEquals(
            $evaluator,
            new ThreeOrLessAsMinusOneZeroOtherwiseEvaluator(),
            'ThreeOrLessAsMinusOneZeroOtherwise should be immutable'
        );
    }

}