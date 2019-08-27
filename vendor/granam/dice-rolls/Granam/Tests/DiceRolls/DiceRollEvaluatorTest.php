<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls;

use Granam\DiceRolls\DiceRollEvaluator;
use PHPUnit\Framework\TestCase;

class DiceRollEvaluatorTest extends TestCase
{

    /**
     * @test
     */
    public function I_can_use_dice_roll_evaluator_interface(): void
    {
        self::assertTrue(interface_exists(DiceRollEvaluator::class));
        $reflection = new \ReflectionClass(DiceRollEvaluator::class);
        $methods = $reflection->getMethods();
        self::assertCount(1, $methods);
        self::assertTrue($reflection->hasMethod('evaluateDiceRoll'));
        $evaluateDiceRoll = new \ReflectionMethod(DiceRollEvaluator::class, 'evaluateDiceRoll');
        self::assertSame(1, $evaluateDiceRoll->getNumberOfParameters());
        self::assertSame(1, $evaluateDiceRoll->getNumberOfRequiredParameters());
        $parameters = $evaluateDiceRoll->getParameters();
        /** @var \ReflectionParameter $parameter */
        $parameter = current($parameters);
        self::assertSame('diceRoll', $parameter->getName());
    }
}