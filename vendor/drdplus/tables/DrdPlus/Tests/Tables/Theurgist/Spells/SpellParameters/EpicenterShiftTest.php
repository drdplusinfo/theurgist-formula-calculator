<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;

class EpicenterShiftTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_its_distance()
    {
        $shift = new EpicenterShift(['35', '332211'], Tables::getIt());
        self::assertSame(35, $shift->getValue());
        self::assertSame(35, $shift->getDistanceBonus()->getValue());
    }

    /**
     * @test
     */
    public function I_can_create_it_with_precise_distance()
    {
        $shift = new EpicenterShift(
            ['40', '332211'],
            Tables::getIt(),
            $distance = new Distance(102, DistanceUnitCode::METER, Tables::getIt()->getDistanceTable())
        );
        self::assertSame(40, $shift->getValue());
        self::assertSame($distance, $shift->getDistance());
        self::assertEquals($distance->getBonus(), $shift->getDistance()->getBonus());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\EpicenterShiftDistanceDoesNotMatch
     * @expectedExceptionMessageRegExp ~0~
     */
    public function I_can_not_create_it_with_non_matching_distance()
    {
        new EpicenterShift(['40', '332211'], Tables::getIt(), new Distance(1, DistanceUnitCode::METER, Tables::getIt()->getDistanceTable()));
    }
}