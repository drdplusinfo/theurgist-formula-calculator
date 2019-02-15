<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Races;

use DrdPlus\Codes\RaceCode;
use DrdPlus\Tables\Partials\AbstractFileTable;

/**
 * See PPH page 130 left column, @link https://pph.drdplus.info/#tabulka_rozsahu_zraku
 */
class SightRangesTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/sight_ranges.csv';
    }

    public const MAXIMAL_LIGHTING = 'maximal_lighting';
    public const MINIMAL_LIGHTING = 'minimal_lighting';
    public const ADAPTABILITY = 'adaptability';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::MAXIMAL_LIGHTING => self::INTEGER,
            self::MINIMAL_LIGHTING => self::INTEGER,
            self::ADAPTABILITY => self::POSITIVE_INTEGER,
        ];
    }

    public const RACE = 'race';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [
            self::RACE,
        ];
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getMaximalLighting(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($raceCode, self::MAXIMAL_LIGHTING);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getMinimalLighting(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($raceCode, self::MINIMAL_LIGHTING);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getAdaptability(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($raceCode, self::ADAPTABILITY);
    }

}