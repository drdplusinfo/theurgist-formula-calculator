<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Speed;

use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfBonus;
use Mockery\MockInterface;

class SpeedBonusTest extends AbstractTestOfBonus
{
    /**
     * @test
     */
    public function I_can_get_distance_covered_per_round()
    {
        $speedBonus = new SpeedBonus(123, $this->createSpeedTable());
        $distanceTable = $this->createDistanceTable();
        $distance = $this->createDistance();
        $distanceTable->shouldReceive('toDistance')
            ->andReturnUsing(function (DistanceBonus $distanceBonus) use ($distance) {
                self::assertSame(123, $distanceBonus->getValue());

                return $distance;
            });
        self::assertSame($distance, $speedBonus->getDistancePerRound($distanceTable));
    }

    /**
     * @return Distance|MockInterface
     */
    private function createDistance(): Distance
    {
        return $this->mockery(Distance::class);
    }

    /**
     * @return \Mockery\MockInterface|SpeedTable
     */
    private function createSpeedTable()
    {
        return $this->mockery(SpeedTable::class);
    }

    /**
     * @return \Mockery\MockInterface|DistanceTable
     */
    private function createDistanceTable()
    {
        return $this->mockery(DistanceTable::class);
    }
}