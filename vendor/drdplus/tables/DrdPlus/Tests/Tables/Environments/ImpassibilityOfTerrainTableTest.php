<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Codes\Environment\TerrainCode;
use DrdPlus\Tables\Environments\TerrainDifficultyPercents;
use DrdPlus\Tables\Environments\ImpassibilityOfTerrainTable;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tests\Tables\TableTest;

class ImpassibilityOfTerrainTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [['terrain', 'impassibility_of_terrain_from', 'impassibility_of_terrain_to']],
            (new ImpassibilityOfTerrainTable())->getHeader()
        );
    }

    /**
     * @test
     */
    public function Values_are_at_most_zero()
    {
        foreach ((new ImpassibilityOfTerrainTable())->getValues() as $row) {
            self::assertLessThanOrEqual(0, $row[1]);
            self::assertLessThan(0, $row[2]);
        }
    }

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        self::assertSame(
            [
                'road' => ['impassibility_of_terrain_from' => 0, 'impassibility_of_terrain_to' => -2],
                'meadow' => ['impassibility_of_terrain_from' => -1, 'impassibility_of_terrain_to' => -4],
                'forest' => ['impassibility_of_terrain_from' => -1, 'impassibility_of_terrain_to' => -8],
                'jungle' => ['impassibility_of_terrain_from' => -6, 'impassibility_of_terrain_to' => -12],
                'swamp' => ['impassibility_of_terrain_from' => -10, 'impassibility_of_terrain_to' => -18],
                'mountains' => ['impassibility_of_terrain_from' => -10, 'impassibility_of_terrain_to' => -20],
                'desert' => ['impassibility_of_terrain_from' => -5, 'impassibility_of_terrain_to' => -20],
                'icy_plains' => ['impassibility_of_terrain_from' => -5, 'impassibility_of_terrain_to' => -20],
            ],
            (new ImpassibilityOfTerrainTable())->getIndexedValues()
        );
    }

    /**
     * @test
     * @dataProvider provideTerrainAndExpectedMalusRange
     * @param string $terrainCode
     * @param int $expectedMalusFrom
     * @param int $expectedMalusTo
     */
    public function I_can_get_malus_range_for_every_terrain($terrainCode, $expectedMalusFrom, $expectedMalusTo)
    {
        self::assertSame(
            ['impassibility_of_terrain_from' => $expectedMalusFrom, 'impassibility_of_terrain_to' => $expectedMalusTo],
            (new ImpassibilityOfTerrainTable())->getSpeedMalusValuesRangeForTerrain(TerrainCode::getIt($terrainCode))
        );
    }

    public function provideTerrainAndExpectedMalusRange()
    {
        return [
            ['road', 0, -2],
            ['meadow', -1, -4],
            ['forest', -1, -8],
            ['jungle', -6, -12],
            ['swamp', -10, -18],
            ['mountains', -10, -20],
            ['desert', -5, -20],
            ['icy_plains', -5, -20],
        ];
    }

    /**
     * @test
     * @dataProvider provideDifficultyAndTerrainAndExpectedMalus
     * @param int $difficultyInPercents
     * @param string $terrainCode
     * @param int $expectedMalus
     */
    public function I_can_get_malus_providing_difficulty_for_every_terrain($difficultyInPercents, $terrainCode, $expectedMalus)
    {
        $speedMalusOnTerrain = (new ImpassibilityOfTerrainTable())->getSpeedMalusOnTerrain(
            TerrainCode::getIt($terrainCode),
            new SpeedTable(),
            new TerrainDifficultyPercents($difficultyInPercents)
        );
        self::assertInstanceOf(SpeedBonus::class, $speedMalusOnTerrain);
        self::assertSame($expectedMalus, $speedMalusOnTerrain->getValue());
    }

    public function provideDifficultyAndTerrainAndExpectedMalus()
    {
        return [
            [0, 'road', 0],
            [50, 'road', -1],
            [100, 'road', -2],
            [0, 'meadow', -1],
            [33, 'meadow', -2],
            [66, 'meadow', -3],
            [100, 'meadow', -4],
            [0, 'forest', -1],
            [10, 'forest', -2],
            [20, 'forest', -2],
            [30, 'forest', -3],
            [40, 'forest', -4],
            [50, 'forest', -5],
            [60, 'forest', -5],
            [70, 'forest', -6],
            [80, 'forest', -7],
            [90, 'forest', -7],
            [100, 'forest', -8],
            [0, 'jungle', -6],
            [16, 'jungle', -7],
            [32, 'jungle', -8],
            [48, 'jungle', -9],
            [64, 'jungle', -10],
            [80, 'jungle', -11],
            [99, 'jungle', -12],
            [0, 'swamp', -10],
            [14, 'swamp', -11],
            [24, 'swamp', -12],
            [38, 'swamp', -13],
            [52, 'swamp', -14],
            [66, 'swamp', -15],
            [100, 'swamp', -18],
            [0, 'mountains', -10],
            [45, 'mountains', -15],
            [100, 'mountains', -20],
            [0, 'desert', -5],
            [33, 'desert', -10],
            [100, 'desert', -20],
            [0, 'icy_plains', -5],
            [35, 'icy_plains', -10],
            [100, 'icy_plains', -20],
        ];
    }

    /**
     * @test
     */
    public function I_got_malus_rounded_on_border_values()
    {
        $impassibilityOfTerrainTable = new ImpassibilityOfTerrainTable();
        $speedTable = new SpeedTable();
        $jungleAlmostSixthBonus = $impassibilityOfTerrainTable->getSpeedMalusOnTerrain(
            TerrainCode::getIt(TerrainCode::JUNGLE),
            $speedTable,
            new TerrainDifficultyPercents(8)
        );
        self::assertSame(-6, $jungleAlmostSixthBonus->getValue());
        $jungleSixthBonus = $impassibilityOfTerrainTable->getSpeedMalusOnTerrain(
            TerrainCode::getIt(TerrainCode::JUNGLE),
            $speedTable,
            new TerrainDifficultyPercents(9)
        );
        self::assertSame(-7 /* round((-12 - -6) * (100 / 9)) */, $jungleSixthBonus->getValue());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Environments\Exceptions\UnknownTerrainCode
     * @expectedExceptionMessageRegExp ~seabed~
     */
    public function I_can_not_get_values_for_unknown_terrain()
    {
        (new ImpassibilityOfTerrainTable())->getSpeedMalusValuesRangeForTerrain($this->createTerrainCode('seabed'));
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|TerrainCode
     */
    private function createTerrainCode($value)
    {
        $terrainCode = $this->mockery(TerrainCode::class);
        $terrainCode->shouldReceive('getValue')
            ->andReturn($value);
        $terrainCode->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $terrainCode;
    }
}