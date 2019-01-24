<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\RollOn;

use Granam\DiceRolls\Templates\RollOn\RollOn4Plus;

class RollOn4PlusTest extends AbstractRollOnTest
{

    /**
     * @test
     */
    public function I_get_agreement_on_four_and_more()
    {
        $rollOn4Plus = new RollOn4Plus($this->createRoller());
        for ($value = -1; $value < 6; $value++) {
            self::assertSame($value >= 4, $rollOn4Plus->shouldHappen($value));
        }
        self::assertFalse($rollOn4Plus->shouldHappen(PHP_INT_MIN));
        self::assertTrue($rollOn4Plus->shouldHappen(PHP_INT_MAX));
    }
}