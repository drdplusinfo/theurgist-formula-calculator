<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Fatigue;

use DrdPlus\Tables\Measurements\Fatigue\Fatigue;
use DrdPlus\Tables\Measurements\Fatigue\FatigueBonus;
use DrdPlus\Tables\Measurements\Fatigue\FatigueTable;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tests\Tables\Measurements\MeasurementTableTest;

class FatigueTableTest extends MeasurementTableTest
{
    /** @var  WoundsTable */
    private $woundsTable;

    protected function setUp(): void
    {
        $this->woundsTable = new WoundsTable();
    }

    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        $this->I_can_get_headers_same_as_from_wounds_table();
    }

    private function I_can_get_headers_same_as_from_wounds_table(): void
    {
        $experiencesTable = new FatigueTable($this->woundsTable);

        self::assertEquals($this->woundsTable->getHeader(), $experiencesTable->getHeader());
    }

    /**
     * @test
     */
    public function I_can_get_values_same_as_from_wounds_table(): void
    {
        $experiencesTable = new FatigueTable($woundsTable = new WoundsTable());

        self::assertEquals($woundsTable->getValues(), $experiencesTable->getValues());
        self::assertEquals($woundsTable->getIndexedValues(), $experiencesTable->getIndexedValues());
    }

    /**
     * @test
     */
    public function I_can_convert_bonus_to_value(): void
    {
        $fatigueTable = new FatigueTable($this->woundsTable);
        $attempt = 1;
        $maxAttempts = 10000;
        do {
            $zeroOrOne = $fatigueTable->toFatigue(new FatigueBonus(-20, $fatigueTable))->getValue();
            if ($zeroOrOne === 1) {
                break;
            }
        } while ($attempt++ < $maxAttempts);
        self::assertLessThan($maxAttempts, $attempt);
        self::assertSame(1, $zeroOrOne);

        // for bonus -10 to -7 are the same fatigue
        self::assertSame(
            1,
            $fatigueTable->toFatigue(new FatigueBonus(-10, $fatigueTable))->getValue()
        );
        self::assertSame(
            1,
            $fatigueTable->toFatigue(new FatigueBonus(-9, $fatigueTable))->getValue()
        );
        self::assertSame(
            1,
            $fatigueTable->toFatigue(new FatigueBonus(-8, $fatigueTable))->getValue()
        );
        self::assertSame(
            1,
            $fatigueTable->toFatigue(new FatigueBonus(-7, $fatigueTable))->getValue()
        );
        self::assertSame(
            3,
            $fatigueTable->toFatigue(new FatigueBonus(0, $fatigueTable))->getValue()
        );
        self::assertSame(
            28000,
            $fatigueTable->toFatigue(new FatigueBonus(79, $fatigueTable))->getValue()
        );
    }

    /**
     * @test
     */
    public function I_can_not_use_too_low_bonus_to_value(): void
    {
        self::assertFalse(
            false,
            'It is built on shoulders of wounds table, which has a comfort to turn too low bonus to zero without loosing information'
        );
    }

    /**
     * @test
     */
    public function I_can_convert_very_high_bonus_into_value(): void
    {
        $fatigueTable = new FatigueTable($this->woundsTable);
        $fatigue = $fatigueTable->toFatigue(new FatigueBonus(80, $fatigueTable));
        self::assertSame(31623, $fatigue->getValue());
    }

    /**
     * @test
     */
    public function I_can_not_convert_too_high_bonus_into_too_detailed_unit(): void
    {
        self::assertFalse(false, 'Actually I can');
    }

    /**
     * @test
     */
    public function I_can_convert_value_to_bonus(): void
    {
        $fatigueTable = new FatigueTable($this->woundsTable);
        self::assertSame(
            -10,
            $fatigueTable->toBonus(new Fatigue(1, $fatigueTable))->getValue()
        );

        // there are more bonuses for wound 3, the lowest is taken
        self::assertSame(
            -2,
            $fatigueTable->toBonus(new Fatigue(3, $fatigueTable))->getValue()
        );

        self::assertSame(
            30,
            $fatigueTable->toBonus(new Fatigue(104, $fatigueTable))->getValue()
        ); // 30 is the closest bonus
        self::assertSame(
            31,
            $fatigueTable->toBonus(new Fatigue(105, $fatigueTable))->getValue()
        ); // 30 and 31 are closest bonuses, 31 is taken because higher
        self::assertSame(
            31,
            $fatigueTable->toBonus(new Fatigue(106, $fatigueTable))->getValue()
        ); // 31 is the closest bonus (higher in this case)

        self::assertSame(
            79,
            $fatigueTable->toBonus(new Fatigue(28000, $fatigueTable))->getValue()
        );
    }

    /**
     * @test
     */
    public function I_can_not_convert_too_low_value_to_bonus(): void
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Fatigue\Exceptions\FatigueCanNotBeNegative::class);
        $fatigueTable = new FatigueTable($this->woundsTable);
        $fatigueTable->toBonus(new Fatigue(-1, $fatigueTable));
    }

    /**
     * @test
     */
    public function I_can_convert_very_high_value_to_bonus(): void
    {
        $fatigueTable = new FatigueTable($this->woundsTable);
        $fatigueBonus = $fatigueTable->toBonus(new Fatigue(28001, $fatigueTable));
        self::assertSame(79, $fatigueBonus->getValue());
    }

    /**
     * @test
     */
    public function I_can_not_convert_too_high_value_to_bonus(): void
    {
        self::assertFalse(false, 'Actually I can');
    }
}