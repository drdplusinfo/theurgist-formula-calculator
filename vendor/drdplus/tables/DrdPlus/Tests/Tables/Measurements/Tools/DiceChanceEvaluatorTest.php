<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Tools;

use DrdPlus\Tables\Measurements\Tools\DiceChanceEvaluator;
use Granam\DiceRolls\Roll;
use Granam\DiceRolls\Templates\Rollers\Roller1d6;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;

class DiceChanceEvaluatorTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_evaluate_chance_by_dice_roll()
    {
        /** @var \Mockery\MockInterface|Roller1d6 $roller */
        $roller = $this->mockery(Roller1d6::class);
        $evaluator = new DiceChanceEvaluator($roller);
        $roller->shouldReceive('roll')
            ->andReturn($this->createRoll(321));
        self::assertSame(0, $evaluator->evaluate(320), 'Higher roll than maximum should result into zero');
        self::assertSame(1, $evaluator->evaluate(321));
        self::assertSame(1, $evaluator->evaluate(322));
    }

    /**
     * @param $value
     * @return Roll|MockInterface
     */
    private function createRoll($value): Roll
    {
        $roll = $this->mockery(Roll::class);
        $roll->shouldReceive('getValue')
            ->andReturn($value);

        return $roll;
    }
}