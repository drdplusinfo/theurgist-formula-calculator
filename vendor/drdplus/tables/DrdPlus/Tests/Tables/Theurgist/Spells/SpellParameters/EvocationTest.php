<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;

class EvocationTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_evocation_time()
    {
        $evocation = new Evocation([123, 0], Tables::getIt());
        self::assertEquals(
            new TimeBonus(123, Tables::getIt()->getTimeTable()),
            $evocation->getEvocationTimeBonus()
        );
    }
}