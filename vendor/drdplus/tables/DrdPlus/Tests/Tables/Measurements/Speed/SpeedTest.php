<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Speed;

use DrdPlus\Codes\Units\SpeedUnitCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Speed\Speed;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;
use Mockery\MockInterface;

class SpeedTest extends AbstractTestOfMeasurement
{

    protected function getDefaultUnit(): string
    {
        return SpeedUnitCode::METER_PER_ROUND;
    }

    protected function getAllUnits(): array
    {
        return SpeedUnitCode::getPossibleValues();
    }

    /**
     * @test
     */
    public function I_can_get_unit_as_code()
    {
        $meters = new Speed(123, SpeedUnitCode::METER_PER_ROUND, new SpeedTable());
        self::assertSame(SpeedUnitCode::getIt(SpeedUnitCode::METER_PER_ROUND), $meters->getUnitCode());
        $kilometers = new Speed(465, SpeedUnitCode::KILOMETER_PER_HOUR, new SpeedTable());
        self::assertSame(SpeedUnitCode::getIt(SpeedUnitCode::KILOMETER_PER_HOUR), $kilometers->getUnitCode());
    }

    /**
     * @test
     */
    public function I_can_get_distance_moved_per_round()
    {
        $distanceTable = new DistanceTable();
        $distance = $this->createDistance();
        $speed = new Speed(123, SpeedUnitCode::METER_PER_ROUND, $this->createSpeedTable($distanceTable, $distance));
        $distancePerRound = $speed->getDistancePerRound($distanceTable);
        self::assertSame($distance, $distancePerRound);
    }

    /**
     * @param DistanceTable $distanceTable
     * @param Distance $distance
     * @return SpeedTable|MockInterface
     */
    private function createSpeedTable(DistanceTable $distanceTable, Distance $distance): SpeedTable
    {
        $speedTable = $this->mockery(SpeedTable::class);
        $speedTable->shouldReceive('toBonus')
            ->andReturn($speedBonus = $this->mockery(SpeedBonus::class));
        $speedBonus->shouldReceive('getDistancePerRound')
            ->with($distanceTable)
            ->andReturn($distance);

        return $speedTable;
    }

    /**
     * @return Distance|MockInterface
     */
    private function createDistance(): Distance
    {
        return $this->mockery(Distance::class);
    }
}