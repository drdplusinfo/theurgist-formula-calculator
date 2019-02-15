<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Body\MovementTypes;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Codes\Transport\MovementTypeCode;
use DrdPlus\Tables\Body\MovementTypes\MovementTypesTable;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tables\Properties\EnduranceInterface;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\TableTest;

class MovementTypesTableTest extends TableTest
{
    /**
     * @var SpeedTable
     */
    private $speedTable;
    /**
     * @var TimeTable
     */
    private $timeTable;

    protected function setUp(): void
    {
        $this->speedTable = new SpeedTable();
        $this->timeTable = new TimeTable();
    }

    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertSame(
            [
                ['movement_type', 'bonus_to_movement_speed', 'hours_per_point_of_fatigue', 'minutes_per_point_of_fatigue', 'rounds_per_point_of_fatigue']
            ],
            $movementTypesTable->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_values(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertSame(
            [
                MovementTypeCode::WAITING => [
                    'bonus_to_movement_speed' => 0,
                    'hours_per_point_of_fatigue' => false,
                    'minutes_per_point_of_fatigue' => false,
                    'rounds_per_point_of_fatigue' => false,
                ],
                MovementTypeCode::WALK => [
                    'bonus_to_movement_speed' => 23,
                    'hours_per_point_of_fatigue' => 1.0,
                    'minutes_per_point_of_fatigue' => false,
                    'rounds_per_point_of_fatigue' => false,
                ],
                MovementTypeCode::RUSH => [
                    'bonus_to_movement_speed' => 26,
                    'hours_per_point_of_fatigue' => 0.5,
                    'minutes_per_point_of_fatigue' => false,
                    'rounds_per_point_of_fatigue' => false,
                ],
                MovementTypeCode::RUN => [
                    'bonus_to_movement_speed' => 32,
                    'hours_per_point_of_fatigue' => false,
                    'minutes_per_point_of_fatigue' => 5.0,
                    'rounds_per_point_of_fatigue' => false,
                ],
                MovementTypeCode::SPRINT => [
                    'bonus_to_movement_speed' => 36,
                    'hours_per_point_of_fatigue' => false,
                    'minutes_per_point_of_fatigue' => false,
                    'rounds_per_point_of_fatigue' => 2.0,
                ]
            ],
            $movementTypesTable->getIndexedValues()
        );
    }

    /**
     * @test
     * @dataProvider provideTypeAndExpectedBonusAndPeriod
     * @param string $type
     * @param SpeedBonus $expectedBonus
     * @param Time|bool $expectedPeriod
     */
    public function I_can_get_bonus_and_time_per_point_of_fatigue(string $type, SpeedBonus $expectedBonus, $expectedPeriod): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        self::assertEquals($expectedBonus, $movementTypesTable->getSpeedBonus($type));
        if ($expectedPeriod instanceof Time) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            self::assertEquals($expectedPeriod, $movementTypesTable->getPeriodForPointOfFatigueOn($type));
        } else {
            self::assertSame($expectedPeriod, $movementTypesTable->getPeriodForPointOfFatigueOn($type));
        }
    }

    public function provideTypeAndExpectedBonusAndPeriod(): array
    {
        $timeTable = new TimeTable();
        $speedTable = new SpeedTable();

        return [
            [MovementTypeCode::WAITING, new SpeedBonus(0, $speedTable), false],
            [MovementTypeCode::WALK, new SpeedBonus(23, $speedTable), new Time(1, TimeUnitCode::HOUR, $timeTable)],
            [MovementTypeCode::RUSH, new SpeedBonus(26, $speedTable), new Time(0.5, TimeUnitCode::HOUR, $timeTable)],
            [MovementTypeCode::RUN, new SpeedBonus(32, $speedTable), new Time(5, TimeUnitCode::MINUTE, $timeTable)],
            [MovementTypeCode::SPRINT, new SpeedBonus(36, $speedTable), new Time(2, TimeUnitCode::ROUND, $timeTable)],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_waiting_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new SpeedBonus(0, $this->speedTable), $movementTypesTable->getSpeedBonusOnWaiting());
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_walk_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new SpeedBonus(23, $this->speedTable), $movementTypesTable->getSpeedBonusOnWalk());
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_rush_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new SpeedBonus(26, $this->speedTable), $movementTypesTable->getSpeedBonusOnRush());
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_run_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new SpeedBonus(32, $this->speedTable), $movementTypesTable->getSpeedBonusOnRun());
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_sprint_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new SpeedBonus(36, $this->speedTable), $movementTypesTable->getSpeedBonusOnSprint());
    }

    /**
     * @test
     */
    public function I_can_get_period_for_point_fatigue_on_walk_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new Time(1, TimeUnitCode::HOUR, $this->timeTable), $movementTypesTable->getPeriodForPointOfFatigueOnWalk());
    }

    /**
     * @test
     */
    public function I_can_get_period_for_point_fatigue_on_rush_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new Time(0.5, TimeUnitCode::HOUR, $this->timeTable), $movementTypesTable->getPeriodForPointOfFatigueOnRush());
    }

    /**
     * @test
     */
    public function I_can_get_period_for_point_fatigue_on_run_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new Time(5, TimeUnitCode::MINUTE, $this->timeTable), $movementTypesTable->getPeriodForPointOfFatigueOnRun());
    }

    /**
     * @test
     */
    public function I_can_get_period_for_point_fatigue_on_sprint_by_simple_getter(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        self::assertEquals(new Time(2, TimeUnitCode::ROUND, $this->timeTable), $movementTypesTable->getPeriodForPointOfFatigueOnSprint());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Body\MovementTypes\Exceptions\UnknownMovementType
     * @expectedExceptionMessageRegExp ~moonwalk~
     */
    public function I_can_not_get_movement_bonus_for_unknown_type(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $movementTypesTable->getSpeedBonus('moonwalk');
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Body\MovementTypes\Exceptions\UnknownMovementType
     * @expectedExceptionMessageRegExp ~sneaking~
     */
    public function I_can_not_get_period_of_fatigue_for_unknown_type(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $movementTypesTable->getPeriodForPointOfFatigueOn('sneaking');
    }

    /**
     * @test
     */
    public function I_can_get_maximum_time_of_sprint(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        $timeBonus = $movementTypesTable->getMaximumTimeBonusToSprint($this->createEndurance(123));
        self::assertSame(123, $timeBonus->getValue());
    }

    /**
     * @test
     */
    public function I_can_get_required_time_of_walk_after_maximum_sprint(): void
    {
        $movementTypesTable = new MovementTypesTable($this->speedTable, $this->timeTable);
        $timeBonus = $movementTypesTable->getRequiredTimeBonusToWalkAfterFullSprint($this->createEndurance(456));
        self::assertSame(476, $timeBonus->getValue());
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|EnduranceInterface
     */
    private function createEndurance($value): EnduranceInterface
    {
        $endurance = $this->mockery(EnduranceInterface::class);
        $endurance->shouldReceive('getValue')
            ->andReturn($value);

        return $endurance;
    }

    /**
     * @test
     */
    public function I_can_get_fatigue_on_walk(): void
    {
        $fatigueOnWalk = Tables::getIt()->getMovementTypesTable()->getFatigueOnWalk(
            new Time(5, Time::HOUR, Tables::getIt()->getTimeTable()),
            Tables::getIt()
        );
        self::assertSame(5, $fatigueOnWalk->getValue());
    }

    /**
     * @test
     */
    public function I_can_get_fatigue_on_rush(): void
    {
        $fatigueOnRush = Tables::getIt()->getMovementTypesTable()->getFatigueOnRush(
            new Time(5, Time::HOUR, Tables::getIt()->getTimeTable()),
            Tables::getIt()
        );
        self::assertSame(10, $fatigueOnRush->getValue());
    }

    /**
     * @test
     */
    public function I_can_get_fatigue_on_run(): void
    {
        $fatigueOnRun = Tables::getIt()->getMovementTypesTable()->getFatigueOnRun(
            new Time(5, Time::HOUR, Tables::getIt()->getTimeTable()),
            Tables::getIt()
        );
        self::assertSame(56, $fatigueOnRun->getValue());
    }

    /**
     * @test
     */
    public function I_can_get_fatigue_on_sprint(): void
    {
        $fatigueOnRun = Tables::getIt()->getMovementTypesTable()->getFatigueOnSprint(
            new Time(56, Time::MINUTE, Tables::getIt()->getTimeTable()),
            Tables::getIt()
        );
        self::assertSame(170, $fatigueOnRun->getValue());
    }
}