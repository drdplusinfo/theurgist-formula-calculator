<?php declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Measurements\Time\TimeBonus;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\PositiveCastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellDuration;

class SpellDurationTest extends PositiveCastingParameterTest
{
    protected function I_can_create_it_with_zero()
    {
        $duration = new SpellDuration(['0', '78=321'], Tables::getIt());
        self::assertSame(0, $duration->getValue());
        self::assertEquals(new AdditionByDifficulty('78=321'), $duration->getAdditionByDifficulty());
        self::assertSame('0 (' . $duration->getAdditionByDifficulty() . ')', (string)$duration);
    }

    protected function I_can_create_it_positive()
    {
        $duration = new SpellDuration(['35689', '332211'], Tables::getIt());
        self::assertSame(35689, $duration->getValue());
        self::assertEquals(new AdditionByDifficulty('332211'), $duration->getAdditionByDifficulty());
        self::assertSame('35689 (' . $duration->getAdditionByDifficulty() . ')', (string)$duration);
    }

    /**
     * @test
     */
    public function I_can_not_create_it_negative()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\InvalidValueForPositiveCastingParameter::class);
        $this->expectExceptionMessageRegExp('~-5~');
        new SpellDuration(['-5'], Tables::getIt());
    }

    /**
     * @test
     */
    public function I_can_get_duration_time()
    {
        $duration = new SpellDuration(['23', '1=2'], Tables::getIt());
        self::assertEquals(new TimeBonus(23, Tables::getIt()->getTimeTable()), $duration->getDurationTimeBonus());
    }
}