<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameterTest;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;

class EpicenterShiftTest extends CastingParameterTest
{
    /**
     * @test
     */
    public function I_can_get_its_distance()
    {
        $shift = new EpicenterShift(['35', '332211']);
        self::assertSame(35, $shift->getValue());
        self::assertEquals(
            (new DistanceBonus(35, $distanceTable = new DistanceTable()))->getDistance(),
            $shift->getDistance($distanceTable)
        );
    }

    /**
     * @test
     */
    public function I_can_create_it_with_precise_distance()
    {
        $distanceTable = new DistanceTable();
        $shift = new EpicenterShift(['40', '332211'], $distance = new Distance(102, DistanceUnitCode::METER, $distanceTable));
        self::assertSame(40, $shift->getValue());
        self::assertEquals(new DistanceBonus(40, $distanceTable), $shift->getDistance($distanceTable)->getBonus());
        self::assertGreaterThan(
            (new DistanceBonus(40, $distanceTable))->getDistance()->getValue(),
            $shift->getDistance($distanceTable)->getValue()
        );
        self::assertSame($distance, $shift->getDistance($distanceTable));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\EpicenterShiftDistanceDoesNotMatch
     * @expectedExceptionMessageRegExp ~0~
     */
    public function I_can_not_create_it_with_non_matching_distance()
    {
        new EpicenterShift(['40', '332211'], new Distance(1, DistanceUnitCode::METER, new DistanceTable()));
    }
}