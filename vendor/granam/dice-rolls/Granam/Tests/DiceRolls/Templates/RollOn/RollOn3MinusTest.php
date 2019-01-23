<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\RollOn;

use Granam\DiceRolls\Templates\RollOn\RollOn3Minus;

class RollOn3MinusTest extends AbstractRollOnTest
{
    /**
     * @test
     */
    public function I_get_agreement_on_three_and_less()
    {
        $rollOn3Minus = new RollOn3Minus($this->createRoller());
        for ($value = -1; $value < 6; $value++) {
            self::assertSame($value <= 3, $rollOn3Minus->shouldHappen($value));
        }
        self::assertTrue($rollOn3Minus->shouldHappen(PHP_INT_MIN));
        self::assertFalse($rollOn3Minus->shouldHappen(PHP_INT_MAX));
    }

}