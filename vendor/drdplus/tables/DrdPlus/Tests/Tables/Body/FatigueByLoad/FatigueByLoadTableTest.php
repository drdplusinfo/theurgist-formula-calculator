<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Body\FatigueByLoad;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Tables\Body\FatigueByLoad\FatigueByLoadTable;
use DrdPlus\Tables\Body\MovementTypes\MovementTypesTable;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tables\Properties\AthleticsInterface;
use DrdPlus\Tests\Tables\TableTest;
use Granam\Integer\IntegerInterface;
use Granam\Integer\PositiveInteger;

class FatigueByLoadTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        $fatigueByLoadTable = new FatigueByLoadTable();
        self::assertSame(
            [['missing_strength_up_to', 'load', 'wearies_like']],
            $fatigueByLoadTable->getHeader()
        );
    }

    /**
     * @test
     * @dataProvider provideMissingStrengthAthleticsAndExpectedLoadName
     * @param int $missingStrength
     * @param int $athletics
     * @param string $expectedLoadName
     */
    public function I_can_get_load_name($missingStrength, $athletics, $expectedLoadName)
    {
        $fatigueByLoadTable = new FatigueByLoadTable();
        self::assertSame($expectedLoadName, $fatigueByLoadTable->getLoadName($missingStrength, $this->createAthletic($athletics)));
    }

    public function provideMissingStrengthAthleticsAndExpectedLoadName()
    {
        return [
            [0, 0, 'none'],
            [-1, 1, 'none'],
            [-6, 3, 'none'],
            [3, 3, 'none'],
            [1, 0, 'moderate'],
            [4, 3, 'moderate'],
            [4, 2, 'moderate'],
            [6, 0, 'moderate'],
            [7, 0, 'medium'],
            [8, 1, 'medium'],
            [12, 0, 'medium'],
            [17, 0, 'heavy'],
            [20, 3, 'heavy'],
            [18, 0, 'extreme'],
            [21, 0, 'extreme'],
            [24, 3, 'extreme'],
        ];
    }

    /**
     * @param int $bonusValue
     * @return \Mockery\MockInterface|AthleticsInterface
     */
    private function createAthletic($bonusValue): AthleticsInterface
    {
        $athletics = $this->mockery(AthleticsInterface::class);
        $athletics->shouldReceive('getAthleticsBonus')
            ->andReturn($bonus = $this->mockery(PositiveInteger::class));
        $bonus->shouldReceive('getValue')
            ->andReturn($bonusValue);

        return $athletics;
    }

    /**
     * @test
     * @dataProvider provideMissingStrengthAthleticsAndExpectedPeriod
     * @param int $missingStrength
     * @param int $athletics
     * @param Time|null $expectedPeriodForPointOfFatigue
     */
    public function I_can_get_period_for_point_of_fatigue($missingStrength, $athletics, Time $expectedPeriodForPointOfFatigue = null)
    {
        $fatigueByLoadTable = new FatigueByLoadTable();
        $periodForPointOfFatigue = $fatigueByLoadTable->getPeriodForPointOfFatigue(
            $missingStrength,
            $this->createAthletic($athletics),
            new MovementTypesTable(new SpeedTable(), new TimeTable())
        );
        if ($expectedPeriodForPointOfFatigue === null) {
            self::assertFalse($periodForPointOfFatigue);
        } else {
            self::assertEquals($expectedPeriodForPointOfFatigue, $periodForPointOfFatigue);
        }
    }

    public function provideMissingStrengthAthleticsAndExpectedPeriod(): array
    {
        $timeTable = new TimeTable();

        return [
            [0, 0, null],
            [-1, 1, null],
            [-6, 3, null],
            [3, 3, null],
            [1, 0, new Time(1, TimeUnitCode::HOUR, $timeTable)],
            [4, 3, new Time(1, TimeUnitCode::HOUR, $timeTable)],
            [4, 2, new Time(1, TimeUnitCode::HOUR, $timeTable)],
            [6, 0, new Time(1, TimeUnitCode::HOUR, $timeTable)],
            [7, 0, new Time(0.5, TimeUnitCode::HOUR, $timeTable)],
            [8, 1, new Time(0.5, TimeUnitCode::HOUR, $timeTable)],
            [12, 0, new Time(0.5, TimeUnitCode::HOUR, $timeTable)],
            [17, 0, new Time(5, TimeUnitCode::MINUTE, $timeTable)],
            [20, 3, new Time(5, TimeUnitCode::MINUTE, $timeTable)],
            [18, 0, new Time(2, TimeUnitCode::ROUND, $timeTable)],
            [21, 0, new Time(2, TimeUnitCode::ROUND, $timeTable)],
            [24, 3, new Time(2, TimeUnitCode::ROUND, $timeTable)],
        ];
    }

    /**
     * @test
     */
    public function I_am_stopped_by_exception_if_want_name_but_can_not_move()
    {
        $this->expectException(\DrdPlus\Tables\Body\FatigueByLoad\Exceptions\OverloadedAndCanNotMove::class);
        (new FatigueByLoadTable())->getLoadName(22, $this->createAthletic(0));
    }

    /**
     * @test
     */
    public function I_am_stopped_by_exception_if_want_period_but_can_not_move()
    {
        $this->expectException(\DrdPlus\Tables\Body\FatigueByLoad\Exceptions\OverloadedAndCanNotMove::class);
        (new FatigueByLoadTable())->getPeriodForPointOfFatigue(
            24,
            $this->createAthletic(2),
            new MovementTypesTable(new SpeedTable(), new TimeTable())
        );
    }
}