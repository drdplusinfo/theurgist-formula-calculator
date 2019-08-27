<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Dice;
use Granam\DiceRolls\Templates\Dices\Dices;
use Granam\Integer\IntegerObject;
use Granam\Tests\Tools\TestWithMockery;

class DicesTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_it()
    {
        $instance = new Dices([$this->mockery(Dice::class)]);
        self::assertNotNull($instance);
    }

    /**
     * @test
     */
    public function without_dices_exception_is_thrown()
    {
        $this->expectException(\LogicException::class);
        new Dices([]);
    }

    /**
     * @test
     */
    public function non_dice_parameter_cause_exception()
    {
        $this->expectException(\LogicException::class);
        new Dices([$this->mockery(Dice::class), new \stdClass()]);
    }

    /**
     * @test
     */
    public function null_as_dice_parameter_cause_exception()
    {
        $this->expectException(\LogicException::class);
        new Dices([$this->mockery(Dice::class), null]);
    }

    /**
     * @test
     */
    public function minimum_is_sum_of_dices_minimum()
    {
        $dices = new Dices([$firstDice = $this->mockery(Dice::class), $secondDice = $this->mockery(Dice::class)]);
        $firstDice->shouldReceive('getMinimum')
            ->once()
            ->andReturn($firstMinimum = $this->mockery(IntegerObject::class));
        $firstMinimum->shouldReceive('getValue')
            ->once()
            ->andReturn($firstMinimumValue = 123);
        $secondDice->shouldReceive('getMinimum')
            ->once()
            ->andReturn($secondMinimum = $this->mockery(IntegerObject::class));
        $secondMinimum->shouldReceive('getValue')
            ->once()
            ->andReturn($secondMinimumValue = 456);
        self::assertSame($firstMinimumValue + $secondMinimumValue, $dices->getMinimum()->getValue());
    }

    /**
     * @test
     */
    public function maximum_is_sum_of_dices_maximum()
    {
        $dices = new Dices([$firstDice = $this->mockery(Dice::class), $secondDice = $this->mockery(Dice::class)]);
        $firstDice->shouldReceive('getMaximum')
            ->once()
            ->andReturn($firstMaximum = $this->mockery(IntegerObject::class));
        $firstMaximum->shouldReceive('getValue')
            ->once()
            ->andReturn($firstMaximumValue = 123);
        $secondDice->shouldReceive('getMaximum')
            ->once()
            ->andReturn($secondMaximum = $this->mockery(IntegerObject::class));
        $secondMaximum->shouldReceive('getValue')
            ->once()
            ->andReturn($secondMaximumValue = 456);
        self::assertSame($firstMaximumValue + $secondMaximumValue, $dices->getMaximum()->getValue());
    }
}