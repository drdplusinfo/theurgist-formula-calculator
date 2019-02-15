<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Wounds;

use DrdPlus\Tables\Measurements\Wounds\Wounds;
use DrdPlus\Tables\Measurements\Wounds\WoundsBonus;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tests\Tables\Measurements\MeasurementTableTest;

class WoundsTableTest extends MeasurementTableTest
{

    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        $woundsTable = new WoundsTable();

        self::assertEquals([['bonus', 'wounds']], $woundsTable->getHeader());
    }

    /**
     * @test
     */
    public function I_can_convert_bonus_to_value(): void
    {
        $woundsTable = new WoundsTable();
        $attempt = 1;
        $maxAttempts = 10000;
        do {
            $zeroOrOne = $woundsTable->toWounds(new WoundsBonus(-20, $woundsTable))->getValue();
            if ($zeroOrOne === 1) {
                break;
            }
        } while ($attempt++ < $maxAttempts);
        self::assertLessThan($maxAttempts, $attempt);
        self::assertSame(1, $zeroOrOne);

        // for bonus -10 to -7 are the same wounds
        self::assertSame(1, $woundsTable->toWounds(new WoundsBonus(-10, $woundsTable))->getValue());
        self::assertSame(1, $woundsTable->toWounds(new WoundsBonus(-9, $woundsTable))->getValue());
        self::assertSame(1, $woundsTable->toWounds(new WoundsBonus(-8, $woundsTable))->getValue());
        self::assertSame(1, $woundsTable->toWounds(new WoundsBonus(-7, $woundsTable))->getValue());

        self::assertSame(3, $woundsTable->toWounds(new WoundsBonus(0, $woundsTable))->getValue());
        self::assertSame(28000, $woundsTable->toWounds(new WoundsBonus(79, $woundsTable))->getValue());
    }

    /**
     * @test
     */
    public function I_get_zero_wounds_for_bonus_so_low_so_its_out_of_range(): void
    {
        $woundsTable = new WoundsTable();
        $attempt = 1;
        $maxAttempts = 10000;
        do {
            $zeroOrOne = $woundsTable->toWounds(new WoundsBonus(-20, $woundsTable))->getValue();
            if ($zeroOrOne === 1) {
                break;
            }
        } while ($attempt++ < $maxAttempts);
        self::assertLessThan($maxAttempts, $attempt);
        self::assertSame(1, $zeroOrOne);

        $wounds = $woundsTable->toWounds(new WoundsBonus(-21, $woundsTable));
        self::assertSame(0, $wounds->getValue());
        $wounds = $woundsTable->toWounds(new WoundsBonus(-22, $woundsTable));
        self::assertSame(0, $wounds->getValue());
        $wounds = $woundsTable->toWounds(new WoundsBonus(-999, $woundsTable));
        self::assertSame(0, $wounds->getValue());
    }

    /**
     * @test
     */
    public function I_can_not_use_too_low_bonus_to_value(): void
    {
        self::assertFalse(false, 'Wounds table has a comfort to turn too low bonus to zero without loosing information');
    }

    /**
     * @test
     * @expectedException \OutOfRangeException
     */
    public function I_can_not_convert_too_high_bonus_into_too_detailed_unit(): void
    {
        $woundsTable = new WoundsTable();
        $woundsTable->toWounds(new WoundsBonus(80, $woundsTable));
    }

    /**
     * @test
     */
    public function I_can_convert_value_to_bonus(): void
    {
        $woundsTable = new WoundsTable();
        self::assertSame(-10, $woundsTable->toBonus(new Wounds(1, $woundsTable))->getValue());

        // there are more bonuses for wound 3, the lowest is taken
        self::assertSame(-2, $woundsTable->toBonus(new Wounds(3, $woundsTable))->getValue());

        self::assertSame(30, $woundsTable->toBonus(new Wounds(104, $woundsTable))->getValue()); // 30 is the closest bonus
        self::assertSame(31, $woundsTable->toBonus(new Wounds(105, $woundsTable))->getValue()); // 30 and 31 are closest bonuses, 31 is taken because higher
        self::assertSame(31, $woundsTable->toBonus(new Wounds(106, $woundsTable))->getValue()); // 31 is the closest bonus (higher in this case)

        self::assertSame(79, $woundsTable->toBonus(new Wounds(28000, $woundsTable))->getValue());
    }

    /**
     * @test
     */
    public function I_can_convert_zero_value_to_bonus(): void
    {
        $woundsTable = new WoundsTable();
        $bonus = $woundsTable->toBonus(new Wounds(0, $woundsTable));
        self::assertSame(-21, $bonus->getValue());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    public function I_can_not_convert_too_low_value_to_bonus(): void
    {
        $woundsTable = new WoundsTable();
        $woundsTable->toBonus($this->createWounds(-1));
    }

    /**
     * @param int $value
     * @return Wounds|\Mockery\MockInterface
     */
    private function createWounds(int $value): Wounds
    {
        $wounds = $this->mockery(Wounds::class);
        $wounds->shouldReceive('getValue')
            ->andReturn($value);
        $wounds->shouldReceive('getUnit')
            ->andReturn(Wounds::WOUNDS);

        return $wounds;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    public function I_can_not_convert_too_high_value_to_bonus(): void
    {
        $woundsTable = new WoundsTable();
        $woundsTable->toBonus(new Wounds(28001, $woundsTable));
    }
}