<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
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
        self::assertEquals(new DistanceBonus(35, Tables::getIt()->getDistanceTable()), $shift->getDistanceBonus());
        self::assertEquals(new Distance(56, Distance::METER, Tables::getIt()->getDistanceTable()), $shift->getDistance());
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
     */
    public function I_can_not_create_it_with_non_matching_distance()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\EpicenterShiftDistanceDoesNotMatch::class);
        $this->expectExceptionMessageRegExp('~0~');
        new EpicenterShift(['40', '332211'], Tables::getIt(), new Distance(1, DistanceUnitCode::METER, Tables::getIt()->getDistanceTable()));
    }
}