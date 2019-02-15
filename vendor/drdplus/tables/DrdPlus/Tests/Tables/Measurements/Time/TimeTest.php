<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Time;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;

class TimeTest extends AbstractTestOfMeasurement
{

    protected function getDefaultUnit(): string
    {
        return TimeUnitCode::ROUND;
    }

    protected function getAllUnits(): array
    {
        return [
            TimeUnitCode::ROUND,
            TimeUnitCode::MINUTE,
            TimeUnitCode::HOUR,
            TimeUnitCode::DAY,
            TimeUnitCode::MONTH,
            TimeUnitCode::YEAR,
        ];
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_use_local_constants_instead_of_those_from_unit_code_class()
    {
        $timeConstants = (new \ReflectionClass(Time::class))->getConstants();
        $timeUnitConstants = (new \ReflectionClass(TimeUnitCode::class))->getConstants();
        self::assertCount(0, array_diff($timeUnitConstants, $timeConstants));
        self::assertCount(0, array_diff(array_keys($timeUnitConstants), array_keys($timeConstants)));
    }

    /**
     * @test
     * @dataProvider provideUnsupportedUnitToRoundsConversion
     * @expectedException \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     * @param $value
     * @param string $unit
     */
    public function I_got_null_on_find_and_exception_on_get_of_unsupported_to_rounds_conversion($value, string $unit)
    {
        $timeTable = new TimeTable();
        $time = new Time($value, $unit, $timeTable);
        try {
            self::assertNull($time->findRounds());
        } catch (\Exception $exception) {
            self::fail('No exception expected so far, got ' . $exception->getMessage());
        }
        $time->getRounds();
    }

    public function provideUnsupportedUnitToRoundsConversion()
    {
        return [
            [22, TimeUnitCode::MONTH],
            [100, TimeUnitCode::YEAR],
        ];
    }

    /**
     * @test
     * @dataProvider provideUnsupportedUnitToMinutesConversion
     * @expectedException \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     * @param $value
     * @param $unit
     */
    public function I_got_null_on_find_and_exception_on_get_of_unsupported_to_minutes_conversion($value, $unit)
    {
        $timeTable = new TimeTable();
        $time = new Time($value, $unit, $timeTable);
        try {
            self::assertNull($time->findMinutes());
        } catch (\Exception $exception) {
            self::fail('No exception expected so far, got ' . $exception->getTraceAsString());
        }
        $time->getMinutes();
    }

    public function provideUnsupportedUnitToMinutesConversion()
    {
        return [
            [22, TimeUnitCode::MONTH],
            [100, TimeUnitCode::YEAR],
        ];
    }

    /**
     * @test
     * @dataProvider provideUnsupportedUnitToHoursConversion
     * @expectedException \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     * @param $value
     * @param $unit
     */
    public function I_got_null_on_find_and_exception_on_get_of_unsupported_to_hours_conversion($value, $unit)
    {
        $timeTable = new TimeTable();
        $time = new Time($value, $unit, $timeTable);
        try {
            self::assertNull($time->findHours());
        } catch (\Exception $exception) {
            self::fail('No exception expected so far, got ' . $exception->getTraceAsString());
        }
        $time->getHours();
    }

    public function provideUnsupportedUnitToHoursConversion(): array
    {
        return [
            [1, TimeUnitCode::ROUND],
            [100, TimeUnitCode::YEAR],
        ];
    }

    /**
     * @test
     * @dataProvider provideUnsupportedUnitToDaysConversion
     * @expectedException \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     * @param $value
     * @param $unit
     */
    public function I_got_null_on_find_and_exception_on_get_of_unsupported_to_days_conversion(int $value, string $unit)
    {
        $timeTable = new TimeTable();
        $time = new Time($value, $unit, $timeTable);
        try {
            self::assertNull($time->findDays());
        } catch (\Exception $exception) {
            self::fail('No exception expected so far, got ' . $exception->getTraceAsString());
        }
        $time->getDays();
    }

    public function provideUnsupportedUnitToDaysConversion(): array
    {
        return [
            [1, TimeUnitCode::ROUND],
            [100, TimeUnitCode::YEAR],
        ];
    }

    /**
     * @test
     * @dataProvider provideUnsupportedUnitToMonthsConversion
     * @expectedException \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     * @param $value
     * @param $unit
     */
    public function I_got_null_on_find_and_exception_on_get_of_unsupported_to_months_conversion(int $value, string $unit): void
    {
        $timeTable = new TimeTable();
        $time = new Time($value, $unit, $timeTable);
        try {
            self::assertNull($time->findMonths());
        } catch (\Exception $exception) {
            self::fail('No exception expected so far, got ' . $exception->getTraceAsString());
        }
        $time->getMonths();
    }

    public function provideUnsupportedUnitToMonthsConversion(): array
    {
        return [
            [1, TimeUnitCode::ROUND],
            [20, TimeUnitCode::MINUTE],
            [100, TimeUnitCode::YEAR],
        ];
    }

    /**
     * @test
     * @dataProvider provideUnsupportedUnitToYearsConversion
     * @expectedException \DrdPlus\Tables\Measurements\Time\Exceptions\CanNotConvertTimeToRequiredUnit
     * @param $value
     * @param $unit
     */
    public function I_got_null_on_find_and_exception_on_get_of_unsupported_to_years_conversion(int $value, string $unit): void
    {
        $timeTable = new TimeTable();
        $time = new Time($value, $unit, $timeTable);
        try {
            self::assertNull($time->findYears());
        } catch (\Exception $exception) {
            self::fail('No exception expected so far, got ' . $exception->getTraceAsString());
        }
        $time->getYears();
    }

    public function provideUnsupportedUnitToYearsConversion(): array
    {
        return [
            [1, TimeUnitCode::ROUND],
            [12, TimeUnitCode::MINUTE],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_hours_per_day_as_constant(): void
    {
        self::assertSame((new Time(1, TimeUnitCode::DAY, new TimeTable()))->getHours()->getValue(), Time::HOURS_PER_DAY);
    }

    /**
     * This tests equals to that on PPH page 11 right column
     *
     * @test
     */
    public function I_get_zero_as_bonus_for_one_round(): void
    {
        self::assertSame(
            0,
            (new Time(1, TimeUnitCode::ROUND, new TimeTable()))->getBonus()->getValue(),
            'First available bonus should be taken if more than single one matches the value'
        );
    }

    /**
     * @test
     */
    public function I_can_get_unit_as_code(): void
    {
        $day = new Time(1, TimeUnitCode::DAY, new TimeTable());
        self::assertSame(TimeUnitCode::getIt(TimeUnitCode::DAY), $day->getUnitCode());
        $year = new Time(1, TimeUnitCode::YEAR, new TimeTable());
        self::assertSame(TimeUnitCode::getIt(TimeUnitCode::YEAR), $year->getUnitCode());
    }

    /**
     * @test
     */
    public function I_can_get_it_in_lesser_unit(): void
    {
        $day = new Time(1, TimeUnitCode::DAY, new TimeTable());
        $inHours = $day->findInLesserUnit();
        self::assertEquals($day->getHours(), $inHours);
        $inMinutes = $inHours->findInLesserUnit();
        self::assertEquals($inHours->getMinutes(), $inMinutes);
        self::assertEquals($inMinutes->getRounds(), $inMinutes->findInLesserUnit());
    }
}