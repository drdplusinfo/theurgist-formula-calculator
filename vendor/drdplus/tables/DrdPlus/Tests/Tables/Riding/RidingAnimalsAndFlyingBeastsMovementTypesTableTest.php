<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Riding;

use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Codes\Transport\RidingAnimalMovementCode;
use DrdPlus\Tables\Body\MovementTypes\MovementTypesTable;
use DrdPlus\Tables\Properties\EnduranceInterface;
use DrdPlus\Tables\Riding\RidingAnimalsAndFlyingBeastsMovementTypesTable;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tests\Tables\TableTest;

class RidingAnimalsAndFlyingBeastsMovementTypesTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        self::assertSame(
            [
                ['movement_type', 'bonus_to_movement_speed', 'fatigue_like'],
            ],
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable = new SpeedTable(),
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_values(): void
    {
        self::assertSame(
            [
                RidingAnimalsAndFlyingBeastsMovementTypesTable::STILL => [
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::BONUS_TO_MOVEMENT_SPEED => 0,
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::FATIGUE_LIKE => MovementTypesTable::WAITING,
                ],
                RidingAnimalsAndFlyingBeastsMovementTypesTable::GAIT => [
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::BONUS_TO_MOVEMENT_SPEED => 23,
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::FATIGUE_LIKE => MovementTypesTable::WALK,
                ],
                RidingAnimalsAndFlyingBeastsMovementTypesTable::TROT => [
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::BONUS_TO_MOVEMENT_SPEED => 27,
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::FATIGUE_LIKE => MovementTypesTable::RUSH,
                ],
                RidingAnimalsAndFlyingBeastsMovementTypesTable::CANTER => [
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::BONUS_TO_MOVEMENT_SPEED => 34,
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::FATIGUE_LIKE => MovementTypesTable::RUN,
                ],
                RidingAnimalsAndFlyingBeastsMovementTypesTable::GALLOP => [
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::BONUS_TO_MOVEMENT_SPEED => 39,
                    RidingAnimalsAndFlyingBeastsMovementTypesTable::FATIGUE_LIKE => MovementTypesTable::SPRINT,
                ],
            ],
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable = new SpeedTable(),
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getIndexedValues()
        );
    }

    /**
     * @test
     * @dataProvider provideMovementAndExpectedBonus
     * @param $ridingAnimalMovement
     * @param $expectedSpeedBonus
     */
    public function I_can_get_speed_bonus($ridingAnimalMovement, $expectedSpeedBonus)
    {
        self::assertEquals(
            new SpeedBonus($expectedSpeedBonus, $speedTable = new SpeedTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable,
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getSpeedBonus(RidingAnimalMovementCode::getIt($ridingAnimalMovement))
        );
    }

    public function provideMovementAndExpectedBonus(): array
    {
        return [
            [RidingAnimalMovementCode::STILL, 0],
            [RidingAnimalMovementCode::GALLOP, 39],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_when_still_by_simple_getter()
    {
        self::assertEquals(
            new SpeedBonus(0, $speedTable = new SpeedTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable,
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getSpeedBonusWhenStill()
        );
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_gait_by_simple_getter()
    {
        self::assertEquals(
            new SpeedBonus(23, $speedTable = new SpeedTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable,
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getSpeedBonusOnGait()
        );
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_trot_by_simple_getter()
    {
        self::assertEquals(
            new SpeedBonus(27, $speedTable = new SpeedTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable,
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getSpeedBonusOnTrot()
        );
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_canter_by_simple_getter()
    {
        self::assertEquals(
            new SpeedBonus(34, $speedTable = new SpeedTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable,
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getSpeedBonusOnCanter()
        );
    }

    /**
     * @test
     */
    public function I_can_get_speed_bonus_on_gallop_by_simple_getter()
    {
        self::assertEquals(
            new SpeedBonus(39, $speedTable = new SpeedTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable,
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getSpeedBonusOnGallop()
        );
    }

    /**
     * @test
     * @dataProvider provideMovementAndExpectedPeriodOfFatigue
     * @param $movementCode
     * @param Time $period
     */
    public function I_can_get_period_of_point_of_fatigue($movementCode, Time $period)
    {
        self::assertEquals(
            $period,
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable = new SpeedTable(),
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getPeriodForPointOfFatigue(RidingAnimalMovementCode::getIt($movementCode))
        );
    }

    public function provideMovementAndExpectedPeriodOfFatigue(): array
    {
        $movementTypesTable = new MovementTypesTable(new SpeedTable(), new TimeTable());

        return [
            [RidingAnimalMovementCode::CANTER, $movementTypesTable->getPeriodForPointOfFatigueOnRun()],
            [RidingAnimalMovementCode::GALLOP, $movementTypesTable->getPeriodForPointOfFatigueOnSprint()],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_period_of_point_of_fatigue_for_gait()
    {
        self::assertEquals(
            new Time(1, TimeUnitCode::HOUR, new TimeTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable = new SpeedTable(),
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getPeriodForPointOfFatigueOnGait()
        );
    }

    /**
     * @test
     */
    public function I_can_get_period_of_point_of_fatigue_for_trot()
    {
        self::assertEquals(
            new Time(0.5, TimeUnitCode::HOUR, new TimeTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable = new SpeedTable(),
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getPeriodForPointOfFatigueOnTrot()
        );
    }

    /**
     * @test
     */
    public function I_can_get_period_of_point_of_fatigue_for_canter()
    {
        self::assertEquals(
            new Time(5, TimeUnitCode::MINUTE, new TimeTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable = new SpeedTable(),
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getPeriodForPointOfFatigueOnCanter()
        );
    }

    /**
     * @test
     */
    public function I_can_get_period_of_point_of_fatigue_for_gallop()
    {
        self::assertEquals(
            new Time(2, TimeUnitCode::ROUND, new TimeTable()),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable = new SpeedTable(),
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getPeriodForPointOfFatigueOnGallop()
        );
    }

    /**
     * @test
     */
    public function I_can_get_maximum_time_bonus_to_gallop()
    {
        $speedTable = new SpeedTable();
        $timeTable = new TimeTable();
        $movementTypesTable = new MovementTypesTable($speedTable, $timeTable);
        $endurance = $this->createEndurance(12);

        self::assertEquals(
            $movementTypesTable->getMaximumTimeBonusToSprint($endurance),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable,
                new MovementTypesTable($speedTable, $timeTable)
            ))->getMaximumTimeBonusToGallop($endurance)
        );
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
    public function I_can_get_required_time_bonus_to_walk_after_full_gallop(): void
    {
        $speedTable = new SpeedTable();
        $timeTable = new TimeTable();
        $movementTypesTable = new MovementTypesTable($speedTable, $timeTable);
        $endurance = $this->createEndurance(12);

        self::assertEquals(
            $movementTypesTable->getRequiredTimeBonusToWalkAfterFullSprint($endurance),
            (new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $speedTable = new SpeedTable(),
                new MovementTypesTable($speedTable, new TimeTable())
            ))->getRequiredTimeBonusToWalkAfterFullGallop($endurance)
        );
    }

}