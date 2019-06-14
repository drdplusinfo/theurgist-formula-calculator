<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonActivationDuration;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;

class DemonActivationDurationTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_activation_duration()
    {
        $demonActivationDuration = new DemonActivationDuration([123, 0], Tables::getIt());
        self::assertSame(123, $demonActivationDuration->getValue());
        self::assertEquals(new TimeBonus(123, Tables::getIt()->getTimeTable()), $demonActivationDuration->getDurationTimeBonus());
    }
}