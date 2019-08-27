<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\RollOn;

use Granam\DiceRolls\Roll;
use Granam\DiceRolls\Roller;
use Granam\DiceRolls\RollOn;
use Granam\Tests\Tools\TestWithMockery;

abstract class AbstractRollOnTest extends TestWithMockery
{

    protected function setUp(): void
    {
        parent::setUp();
        if (!\defined('PHP_INT_MIN')) {
            \define('PHP_INT_MIN', (int)(PHP_INT_MAX + 1)); // overflow results into lowest negative integer
        }
    }

    /**
     * @test
     */
    public function I_get_expected_dice_rolls(): void
    {
        $rollOn = $this->createRollOn($this->createRoller($rollSequenceStart = 123, $diceRolls = ['foo']));
        self::assertSame($diceRolls, $rollOn->rollDices($rollSequenceStart));
    }

    /**
     * @param Roller $roller
     * @return RollOn
     */
    protected function createRollOn(Roller $roller): RollOn
    {
        $sutClass = \preg_replace('~[\\\]Tests([\\\].+[\\\]\w+)Test$~', '$1', static::class);

        return new $sutClass($roller);
    }

    /**
     * @param $rollSequenceStart
     * @param $diceRolls
     * @return \Mockery\MockInterface|Roller
     */
    protected function createRoller(int $rollSequenceStart = 1, array $diceRolls = [])
    {
        $roller = $this->mockery(Roller::class);
        $roller->shouldReceive('roll')
            ->zeroOrMoreTimes()
            ->with($rollSequenceStart)
            ->andReturn($roll = $this->mockery(Roll::class));
        $roll->shouldReceive('getDiceRolls')
            ->andReturn($diceRolls);

        return $roller;
    }
}