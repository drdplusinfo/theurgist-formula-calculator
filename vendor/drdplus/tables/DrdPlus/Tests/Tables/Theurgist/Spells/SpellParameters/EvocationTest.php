<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;

class EvocationTest extends PositiveCastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_evocation_time()
    {
        $evocation = new Evocation([123]);
        self::assertEquals(
            (new TimeBonus(123, $timeTable = new TimeTable()))->getTime(),
            $evocation->getEvocationTime($timeTable)
        );
    }
}