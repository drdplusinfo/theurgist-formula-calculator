<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Templates\Dices\Dice1d4;

class Dice1d4Test extends AbstractPredefinedDiceTest
{
    /**
     * @test
     */
    public function I_got_one_as_minimum()
    {
        $dice = new Dice1d4();
        self::assertSame(1, $dice->getMinimum()->getValue());
    }

    /**
     * @test
     */
    public function I_got_four_as_maximum()
    {
        $dice = new Dice1d4();
        self::assertSame(4, $dice->getMaximum()->getValue());
    }
}