<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Races;

use DrdPlus\Codes\RaceCode;
use DrdPlus\Tables\Races\SightRangesTable;
use DrdPlus\Tests\Tables\TableTest;

class SightRangesTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [['race', 'maximal_lighting', 'minimal_lighting', 'adaptability']],
            (new SightRangesTable())->getHeader()
        );
    }

    /**
     * @test
     * @dataProvider provideRaceAndExpectedMaximalLighting
     * @param string $race
     * @param int $expectedMaximalLighting
     */
    public function I_can_get_maximal_lighting($race, $expectedMaximalLighting)
    {
        self::assertSame($expectedMaximalLighting, (new SightRangesTable())->getMaximalLighting(RaceCode::getIt($race)));
    }

    public function provideRaceAndExpectedMaximalLighting()
    {
        return [
            [RaceCode::HUMAN, 55],
            [RaceCode::ELF, 50],
            [RaceCode::DWARF, 50],
            [RaceCode::HOBBIT, 55],
            [RaceCode::KROLL, 50],
            [RaceCode::ORC, 40],
        ];
    }

    /**
     * @test
     * @dataProvider provideRaceAndExpectedMinimalLighting
     * @param string $race
     * @param int $expectedMinimalLighting
     */
    public function I_can_get_minimal_lighting($race, $expectedMinimalLighting)
    {
        self::assertSame($expectedMinimalLighting, (new SightRangesTable())->getMinimalLighting(RaceCode::getIt($race)));
    }

    public function provideRaceAndExpectedMinimalLighting()
    {
        return [
            [RaceCode::HUMAN, 0],
            [RaceCode::ELF, -5],
            [RaceCode::DWARF, -40],
            [RaceCode::HOBBIT, -5],
            [RaceCode::KROLL, -20],
            [RaceCode::ORC, -40],
        ];
    }

    /**
     * @test
     * @dataProvider provideRaceAndExpectedAdaptability
     * @param string $race
     * @param int $expectedAdaptability
     */
    public function I_can_get_adaptability($race, $expectedAdaptability)
    {
        self::assertSame($expectedAdaptability, (new SightRangesTable())->getAdaptability(RaceCode::getIt($race)));
    }

    public function provideRaceAndExpectedAdaptability()
    {
        return [
            [RaceCode::HUMAN, 10],
            [RaceCode::ELF, 10],
            [RaceCode::DWARF, 8],
            [RaceCode::HOBBIT, 10],
            [RaceCode::KROLL, 9],
            [RaceCode::ORC, 8],
        ];
    }

}