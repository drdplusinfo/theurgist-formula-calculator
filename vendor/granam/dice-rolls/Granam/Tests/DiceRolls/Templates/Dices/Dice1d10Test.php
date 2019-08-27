<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Templates\Dices\Dice1d10;

class Dice1d10Test extends AbstractPredefinedDiceTest
{
    /**
     * @test
     */
    public function Its_minimum_is_one(): void
    {
        $dice = new Dice1d10();
        self::assertSame(1, $dice->getMinimum()->getValue());
    }

    /**
     * @test
     */
    public function Its_maximum_is_ten(): void
    {
        $dice = new Dice1d10();
        self::assertSame(10, $dice->getMaximum()->getValue());
    }
}