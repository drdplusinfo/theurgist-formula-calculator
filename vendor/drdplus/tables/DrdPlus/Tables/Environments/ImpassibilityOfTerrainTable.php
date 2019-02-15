<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\Environment\TerrainCode;
use DrdPlus\Tables\Measurements\Speed\SpeedBonus;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Calculations\SumAndRound;
use Granam\Tools\ValueDescriber;

/**
 * See PPH page 113 right column top, @link https://pph.drdplus.info/#tabulka_neschudnosti_terenu
 */
class ImpassibilityOfTerrainTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/impassibility_of_terrain.csv';
    }

    public const TERRAIN = 'terrain';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::TERRAIN];
    }

    public const IMPASSIBILITY_OF_TERRAIN_FROM = 'impassibility_of_terrain_from';
    public const IMPASSIBILITY_OF_TERRAIN_TO = 'impassibility_of_terrain_to';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::IMPASSIBILITY_OF_TERRAIN_FROM => self::INTEGER,
            self::IMPASSIBILITY_OF_TERRAIN_TO => self::INTEGER,
        ];
    }

    /**
     * @param TerrainCode $terrainCode
     * @param SpeedTable $speedTable
     * @param TerrainDifficultyPercents $difficultyPercents
     * @return SpeedBonus
     * @throws \DrdPlus\Tables\Environments\Exceptions\UnknownTerrainCode
     * @throws \DrdPlus\Tables\Environments\Exceptions\InvalidTerrainCodeFormat
     */
    public function getSpeedMalusOnTerrain(
        TerrainCode $terrainCode,
        SpeedTable $speedTable,
        TerrainDifficultyPercents $difficultyPercents
    ): SpeedBonus
    {
        // value is zero or negative, so bonus is malus in fact
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new SpeedBonus($this->getSpeedMalusValueForTerrain($terrainCode, $difficultyPercents), $speedTable);
    }

    /**
     * @param TerrainCode $terrainCode
     * @param TerrainDifficultyPercents $difficultyPercents
     * @return int
     * @throws \DrdPlus\Tables\Environments\Exceptions\UnknownTerrainCode
     * @throws \DrdPlus\Tables\Environments\Exceptions\InvalidTerrainCodeFormat
     */
    private function getSpeedMalusValueForTerrain(
        TerrainCode $terrainCode,
        TerrainDifficultyPercents $difficultyPercents
    ): int
    {
        // value is zero or negative, so bonus is malus in fact
        $range = $this->getSpeedMalusValuesRangeForTerrain($terrainCode);
        $difference = $range[self::IMPASSIBILITY_OF_TERRAIN_TO] - $range[self::IMPASSIBILITY_OF_TERRAIN_FROM];
        $addition = $difference * $difficultyPercents->getRate();

        return SumAndRound::round($range[self::IMPASSIBILITY_OF_TERRAIN_FROM] + $addition);
    }

    /**
     * @param TerrainCode $terrainCode
     * @return array|\int[]
     * @throws \DrdPlus\Tables\Environments\Exceptions\UnknownTerrainCode
     */
    public function getSpeedMalusValuesRangeForTerrain(TerrainCode $terrainCode): array
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getRow([$terrainCode]);
        } catch (RequiredRowNotFound $requiredRowDataNotFound) {
            throw new Exceptions\UnknownTerrainCode('Unknown terrain code "' . ValueDescriber::describe($terrainCode) . "'");
        }
    }

}