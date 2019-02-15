<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Body\Healing;

use DrdPlus\Tables\Body\Healing\HealingConditionsPercents;
use DrdPlus\Tests\Tables\Partials\PercentsTest;

class HealingConditionsPercentsTest extends PercentsTest
{
    /**
     * @test
     */
    public function I_can_create_more_than_hundred_of_percents()
    {
        $healingConditionsPercents = new HealingConditionsPercents(101);
        self::assertSame(101, $healingConditionsPercents->getValue());
    }

    public function I_can_not_create_more_than_hundred_of_percents()
    {
        // intentionally empty, because I can
    }

}
