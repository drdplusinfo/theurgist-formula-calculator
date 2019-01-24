<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\RollOn;

use Granam\DiceRolls\Templates\RollOn\RollOn12;

class RollOn12Test extends AbstractRollOnTest
{

    /**
     * @test
     */
    public function I_get_agreement_on_twelve()
    {
        $rollOn12 = new RollOn12($this->createRoller());
        for ($value = -1; $value < 20; $value++) {
            self::assertSame($value === 12, $rollOn12->shouldHappen($value));
        }
        self::assertFalse($rollOn12->shouldHappen(PHP_INT_MIN));
        self::assertFalse($rollOn12->shouldHappen(PHP_INT_MAX));
    }
}