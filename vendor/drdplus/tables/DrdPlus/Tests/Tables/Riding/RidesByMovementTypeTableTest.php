<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Riding;

use DrdPlus\Codes\Transport\RidingAnimalMovementCode;
use DrdPlus\Tables\Riding\Ride;
use DrdPlus\Tables\Riding\RidesByMovementTypeTable;
use DrdPlus\Tests\Tables\TableTest;

class RidesByMovementTypeTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [
                ['movement_type', 'ride', 'additional']
            ],
            (new RidesByMovementTypeTable())->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        self::assertSame(
            [
                RidingAnimalMovementCode::STILL => [
                    RidesByMovementTypeTable::RIDE => -2,
                    RidesByMovementTypeTable::ADDITIONAL => false
                ],
                RidingAnimalMovementCode::GAIT => [
                    RidesByMovementTypeTable::RIDE => 0,
                    RidesByMovementTypeTable::ADDITIONAL => false
                ],
                RidingAnimalMovementCode::TROT => [
                    RidesByMovementTypeTable::RIDE => 2,
                    RidesByMovementTypeTable::ADDITIONAL => false
                ],
                RidingAnimalMovementCode::CANTER => [
                    RidesByMovementTypeTable::RIDE => 4,
                    RidesByMovementTypeTable::ADDITIONAL => false
                ],
                RidingAnimalMovementCode::GALLOP => [
                    RidesByMovementTypeTable::RIDE => 6,
                    RidesByMovementTypeTable::ADDITIONAL => false
                ],
                RidingAnimalMovementCode::JUMPING => [
                    RidesByMovementTypeTable::RIDE => 2,
                    RidesByMovementTypeTable::ADDITIONAL => true
                ],
            ],
            (new RidesByMovementTypeTable())->getIndexedValues()
        );
    }

    /**
     * @test
     * @dataProvider provideMovementAndExpectedRide
     * @param string $movement
     * @param bool $jumping
     * @param int $expectedRideValue
     */
    public function I_can_get_ride_for_any_move($movement, $jumping, $expectedRideValue)
    {
        $ridesTable = new RidesByMovementTypeTable();
        self::assertEquals(
            new Ride($expectedRideValue),
            $ridesTable->getRideFor(RidingAnimalMovementCode::getIt($movement), $jumping)
        );
    }

    public function provideMovementAndExpectedRide()
    {
        return [
            [RidingAnimalMovementCode::STILL, false, -2],
            [RidingAnimalMovementCode::STILL, true, 0], // horse can jump from place
            [RidingAnimalMovementCode::GAIT, false, 0],
            [RidingAnimalMovementCode::GAIT, true, 2],
            [RidingAnimalMovementCode::TROT, false, 2],
            [RidingAnimalMovementCode::TROT, true, 4],
            [RidingAnimalMovementCode::CANTER, false, 4],
            [RidingAnimalMovementCode::CANTER, true, 6],
            [RidingAnimalMovementCode::GALLOP, false, 6],
            [RidingAnimalMovementCode::GALLOP, true, 8],
        ];
    }
}