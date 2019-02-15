<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Codes\Environment\LightSourceCode;
use DrdPlus\Tables\Environments\PowerOfLightSourcesTable;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tests\Tables\TableTest;

class PowerOfLightSourcesTableTest extends TableTest
{
    /**
     * @testF
     */
    public function I_can_get_header()
    {
        self::assertEquals([['light_source', 'power']], (new PowerOfLightSourcesTable())->getHeader());
    }

    /**
     * @test
     * @dataProvider provideLightSourceAndPower
     * @param string $lightSourceValue
     * @param int $expectedPower
     */
    public function I_can_get_power_of_light_source($lightSourceValue, $expectedPower)
    {
        $powerOfLightSourcesTable = new PowerOfLightSourcesTable();
        self::assertEquals(
            $expectedPower,
            $powerOfLightSourcesTable->getPowerOfLightSource(LightSourceCode::getIt($lightSourceValue))
        );
    }

    public function provideLightSourceAndPower()
    {
        return [
            [LightSourceCode::EMBERS_IN_FIRE, -100],
            [LightSourceCode::CANDLE, -40],
            [LightSourceCode::TRIPLE_CANDELABRA_OR_WORSE_TORCH, -30],
            [LightSourceCode::BETTER_TORCH_OR_SEVEN_CANDELABRA, -23],
            [LightSourceCode::LANTERN, -15],
            [LightSourceCode::CAMP_FIRE, -5],
            [LightSourceCode::BALEFIRE, 10],
            [LightSourceCode::LIGHT_HOUSE, 20],
        ];
    }

    /**
     * @test
     * @dataProvider provideLightSourceAndPower
     * @param string $lightSourceValue
     * @param int $expectedPower
     */
    public function I_can_calculate_lighting_quality_in_distance($lightSourceValue, $expectedPower)
    {
        $powerOfLightSourcesTable = new PowerOfLightSourcesTable();
        $lightingQuality = $powerOfLightSourcesTable->calculateLightingQualityInDistance(
            LightSourceCode::getIt($lightSourceValue),
            $this->createDistance(123)
        );
        self::assertSame($expectedPower - 246, $lightingQuality);
    }

    /**
     * @param int $bonusValue
     * @return \Mockery\MockInterface|Distance
     */
    private function createDistance($bonusValue)
    {
        $distance = $this->mockery(Distance::class);
        $distance->shouldReceive('getBonus')
            ->andReturn($distanceBonus = $this->mockery(DistanceBonus::class));
        $distanceBonus->shouldReceive('getValue')
            ->andReturn($bonusValue);

        return $distance;
    }
}