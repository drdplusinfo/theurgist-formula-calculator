<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Duration;

class DurationTest extends PositiveCastingParameterTest
{
    protected function I_can_create_it_with_zero()
    {
        $duration = new Duration(['0', '78=321']);
        self::assertSame(0, $duration->getValue());
        self::assertEquals(new AdditionByDifficulty('78=321'), $duration->getAdditionByDifficulty());
        self::assertSame('0 (' . $duration->getAdditionByDifficulty() . ')', (string)$duration);
    }

    protected function I_can_create_it_positive()
    {
        $duration = new Duration(['35689', '332211']);
        self::assertSame(35689, $duration->getValue());
        self::assertEquals(new AdditionByDifficulty('332211'), $duration->getAdditionByDifficulty());
        self::assertSame('35689 (' . $duration->getAdditionByDifficulty() . ')', (string)$duration);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForPositiveCastingParameter
     * @expectedExceptionMessageRegExp ~-5~
     */
    public function I_can_not_create_it_negative()
    {
        new Duration(['-5']);
    }

    /**
     * @test
     */
    public function I_can_get_duration_time()
    {
        $duration = new Duration(['23', '1=2']);
        $timeTable = new TimeTable();
        self::assertEquals(new Time(14, TimeUnitCode::ROUND, $timeTable), $duration->getDurationTime($timeTable));
    }
}