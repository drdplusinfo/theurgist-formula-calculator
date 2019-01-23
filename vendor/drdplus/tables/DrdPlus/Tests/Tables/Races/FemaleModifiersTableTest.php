<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Races;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\RaceCode;
use DrdPlus\Tables\Races\FemaleModifiersTable;
use DrdPlus\Tests\Tables\TableTest;

class FemaleModifiersTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        self::assertEquals(
            [['race', 'strength', 'agility', 'knack', 'will', 'intelligence', 'charisma', 'body_weight', 'size']],
            $this->getFemaleModifiersTable()->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_human_female_modifiers(): void
    {
        self::assertEquals(
            [
                PropertyCode::STRENGTH => -1,
                PropertyCode::AGILITY => 0,
                PropertyCode::KNACK => 0,
                PropertyCode::WILL => 0,
                PropertyCode::INTELLIGENCE => 0,
                PropertyCode::CHARISMA => 1,
                PropertyCode::BODY_WEIGHT => -1,
                PropertyCode::SIZE => -1,
            ],
            $this->getFemaleModifiersTable()->getHumanModifiers()
        );
    }

    private static $femaleModifiersTable;

    /**
     * @return FemaleModifiersTable
     */
    protected function getFemaleModifiersTable(): FemaleModifiersTable
    {
        if (self::$femaleModifiersTable === null) {
            self::$femaleModifiersTable = new FemaleModifiersTable();
        }

        return self::$femaleModifiersTable;
    }

    /**
     * @test
     */
    public function I_can_get_elf_female_modifiers(): void
    {
        self::assertEquals(
            [
                PropertyCode::STRENGTH => -1,
                PropertyCode::AGILITY => 0,
                PropertyCode::KNACK => 1,
                PropertyCode::WILL => 0,
                PropertyCode::INTELLIGENCE => -1,
                PropertyCode::CHARISMA => 1,
                PropertyCode::BODY_WEIGHT => -1,
                PropertyCode::SIZE => -1,
            ],
            $this->getFemaleModifiersTable()->getElfModifiers()
        );
    }

    /**
     * @test
     */
    public function I_can_get_dwarf_female_modifiers(): void
    {
        self::assertEquals(
            [
                PropertyCode::STRENGTH => 0,
                PropertyCode::AGILITY => 0,
                PropertyCode::KNACK => -1,
                PropertyCode::WILL => 0,
                PropertyCode::INTELLIGENCE => 1,
                PropertyCode::CHARISMA => 0,
                PropertyCode::BODY_WEIGHT => 0,
                PropertyCode::SIZE => 0,
            ],
            $this->getFemaleModifiersTable()->getDwarfModifiers()
        );
    }

    /**
     * @test
     */
    public function I_can_get_hobbit_female_modifiers(): void
    {
        self::assertEquals(
            [
                PropertyCode::STRENGTH => -1,
                PropertyCode::AGILITY => 1,
                PropertyCode::KNACK => -1,
                PropertyCode::WILL => 0,
                PropertyCode::INTELLIGENCE => 0,
                PropertyCode::CHARISMA => 1,
                PropertyCode::BODY_WEIGHT => -1,
                PropertyCode::SIZE => -1,
            ],
            $this->getFemaleModifiersTable()->getHobbitModifiers()
        );
    }

    /**
     * @test
     */
    public function I_can_get_kroll_female_modifiers(): void
    {
        self::assertEquals(
            [
                PropertyCode::STRENGTH => -1,
                PropertyCode::AGILITY => 1,
                PropertyCode::KNACK => 0,
                PropertyCode::WILL => -1,
                PropertyCode::INTELLIGENCE => 0,
                PropertyCode::CHARISMA => 1,
                PropertyCode::BODY_WEIGHT => -1,
                PropertyCode::SIZE => -1,
            ],
            $this->getFemaleModifiersTable()->getKrollModifiers()
        );
    }

    /**
     * @test
     */
    public function I_can_get_orc_female_modifiers(): void
    {
        self::assertEquals(
            [
                PropertyCode::STRENGTH => -1,
                PropertyCode::AGILITY => 0,
                PropertyCode::KNACK => 0,
                PropertyCode::WILL => 1,
                PropertyCode::INTELLIGENCE => 0,
                PropertyCode::CHARISMA => 0,
                PropertyCode::BODY_WEIGHT => -1,
                PropertyCode::SIZE => -1,
            ],
            $this->getFemaleModifiersTable()->getOrcModifiers()
        );
    }

    /**
     * @test
     */
    public function I_got_expected_values(): void
    {
        self::assertEquals(
            [
                RaceCode::HUMAN => [
                    PropertyCode::STRENGTH => -1,
                    PropertyCode::AGILITY => 0,
                    PropertyCode::KNACK => 0,
                    PropertyCode::WILL => 0,
                    PropertyCode::INTELLIGENCE => 0,
                    PropertyCode::CHARISMA => 1,
                    PropertyCode::BODY_WEIGHT => -1,
                    PropertyCode::SIZE => -1,
                ],
                RaceCode::ELF => [
                    PropertyCode::STRENGTH => -1,
                    PropertyCode::AGILITY => 0,
                    PropertyCode::KNACK => 1,
                    PropertyCode::WILL => 0,
                    PropertyCode::INTELLIGENCE => -1,
                    PropertyCode::CHARISMA => 1,
                    PropertyCode::BODY_WEIGHT => -1,
                    PropertyCode::SIZE => -1,
                ],
                RaceCode::DWARF => [
                    PropertyCode::STRENGTH => 0,
                    PropertyCode::AGILITY => 0,
                    PropertyCode::KNACK => -1,
                    PropertyCode::WILL => 0,
                    PropertyCode::INTELLIGENCE => 1,
                    PropertyCode::CHARISMA => 0,
                    PropertyCode::BODY_WEIGHT => 0,
                    PropertyCode::SIZE => 0,
                ],
                RaceCode::HOBBIT => [
                    PropertyCode::STRENGTH => -1,
                    PropertyCode::AGILITY => 1,
                    PropertyCode::KNACK => -1,
                    PropertyCode::WILL => 0,
                    PropertyCode::INTELLIGENCE => 0,
                    PropertyCode::CHARISMA => 1,
                    PropertyCode::BODY_WEIGHT => -1,
                    PropertyCode::SIZE => -1,
                ],
                RaceCode::KROLL => [
                    PropertyCode::STRENGTH => -1,
                    PropertyCode::AGILITY => 1,
                    PropertyCode::KNACK => 0,
                    PropertyCode::WILL => -1,
                    PropertyCode::INTELLIGENCE => 0,
                    PropertyCode::CHARISMA => 1,
                    PropertyCode::BODY_WEIGHT => -1,
                    PropertyCode::SIZE => -1,
                ],
                RaceCode::ORC => [
                    PropertyCode::STRENGTH => -1,
                    PropertyCode::AGILITY => 0,
                    PropertyCode::KNACK => 0,
                    PropertyCode::WILL => 1,
                    PropertyCode::INTELLIGENCE => 0,
                    PropertyCode::CHARISMA => 0,
                    PropertyCode::BODY_WEIGHT => -1,
                    PropertyCode::SIZE => -1,
                ],
            ],
            $this->getFemaleModifiersTable()->getIndexedValues()
        );
    }

    /**
     * @test
     * @dataProvider raceToStrength
     * @param string $raceCode
     * @param int $strength
     */
    public function I_can_get_female_strength_of_any_race(string $raceCode, int $strength): void
    {
        $femaleModifiersTable = new FemaleModifiersTable();

        self::assertSame($strength, $femaleModifiersTable->getStrength(RaceCode::getIt($raceCode)));
    }

    public function raceToStrength(): array
    {
        return [
            [RaceCode::HUMAN, -1],
            [RaceCode::ELF, -1],
            [RaceCode::DWARF, 0],
            [RaceCode::HOBBIT, -1],
            [RaceCode::KROLL, -1],
            [RaceCode::ORC, -1],
        ];
    }

    /**
     * @test
     * @dataProvider raceToAgility
     * @param string $raceCode
     * @param int $agility
     */
    public function I_can_get_female_agility_of_any_race($raceCode, $agility): void
    {
        $femaleModifiersTable = new FemaleModifiersTable();

        self::assertSame($agility, $femaleModifiersTable->getAgility(RaceCode::getIt($raceCode)));
    }

    public function raceToAgility(): array
    {
        return [
            [RaceCode::HUMAN, 0],
            [RaceCode::ELF, 0],
            [RaceCode::DWARF, 0],
            [RaceCode::HOBBIT, 1],
            [RaceCode::KROLL, 1],
            [RaceCode::ORC, 0],
        ];
    }

    /**
     * @test
     * @dataProvider raceToKnack
     * @param string $raceCode
     * @param int $knack
     */
    public function I_can_get_female_knack_of_any_race(string $raceCode, int $knack): void
    {
        $femaleModifiersTable = new FemaleModifiersTable();

        self::assertSame($knack, $femaleModifiersTable->getKnack(RaceCode::getIt($raceCode)));
    }

    public function raceToKnack(): array
    {
        return [
            [RaceCode::HUMAN, 0],
            [RaceCode::ELF, 1],
            [RaceCode::DWARF, -1],
            [RaceCode::HOBBIT, -1],
            [RaceCode::KROLL, 0],
            [RaceCode::ORC, 0],
        ];
    }

    /**
     * @test
     * @dataProvider raceToWill
     * @param string $raceCode
     * @param int $will
     */
    public function I_can_get_female_will_of_any_race($raceCode, $will): void
    {
        $femaleModifiersTable = new FemaleModifiersTable();

        self::assertSame($will, $femaleModifiersTable->getWill(RaceCode::getIt($raceCode)));
    }

    public function raceToWill(): array
    {
        return [
            [RaceCode::HUMAN, 0],
            [RaceCode::ELF, 0],
            [RaceCode::DWARF, 0],
            [RaceCode::HOBBIT, 0],
            [RaceCode::KROLL, -1],
            [RaceCode::ORC, 1],
        ];
    }

    /**
     * @test
     * @dataProvider raceToIntelligence
     * @param string $raceCode
     * @param int $intelligence
     */
    public function I_can_get_female_intelligence_of_any_race($raceCode, $intelligence): void
    {
        $femaleModifiersTable = new FemaleModifiersTable();

        self::assertSame($intelligence, $femaleModifiersTable->getIntelligence(RaceCode::getIt($raceCode)));
    }

    public function raceToIntelligence(): array
    {
        return [
            [RaceCode::HUMAN, 0],
            [RaceCode::ELF, -1],
            [RaceCode::DWARF, 1],
            [RaceCode::HOBBIT, 0],
            [RaceCode::KROLL, 0],
            [RaceCode::ORC, 0],
        ];
    }

    /**
     * @test
     * @dataProvider raceToCharisma
     * @param string $raceCode
     * @param int $charisma
     */
    public function I_can_get_female_charisma_of_any_race($raceCode, $charisma): void
    {
        $femaleModifiersTable = new FemaleModifiersTable();

        self::assertSame($charisma, $femaleModifiersTable->getCharisma(RaceCode::getIt($raceCode)));
    }

    public function raceToCharisma(): array
    {
        return [
            [RaceCode::HUMAN, 1],
            [RaceCode::ELF, 1],
            [RaceCode::DWARF, 0],
            [RaceCode::HOBBIT, 1],
            [RaceCode::KROLL, 1],
            [RaceCode::ORC, 0],
        ];
    }

    /**
     * @test
     * @dataProvider raceToWeight
     * @param string $raceCode
     * @param int $charisma
     */
    public function I_can_get_female_weight_simple_bonus_of_any_race($raceCode, $charisma): void
    {
        $femaleModifiersTable = new FemaleModifiersTable();

        self::assertSame($charisma, $femaleModifiersTable->getWeightBonus(RaceCode::getIt($raceCode)));
        // weight modifier has to be same as strength modifier
        self::assertSame(
            $femaleModifiersTable->getStrength(RaceCode::getIt($raceCode)),
            $femaleModifiersTable->getWeightBonus(RaceCode::getIt($raceCode))
        );
    }

    public function raceToWeight(): array
    {
        return [
            [RaceCode::HUMAN, -1],
            [RaceCode::ELF, -1],
            [RaceCode::DWARF, 0],
            [RaceCode::HOBBIT, -1],
            [RaceCode::KROLL, -1],
            [RaceCode::ORC, -1],
        ];
    }

    /**
     * @test
     * @dataProvider raceToSize
     * @param string $raceCode
     * @param int $size
     */
    public function I_can_get_female_size_of_any_race($raceCode, $size): void
    {
        $femaleModifiersTable = new FemaleModifiersTable();

        self::assertSame($size, $femaleModifiersTable->getSize(RaceCode::getIt($raceCode)));
        // size modifier has to be same as strength modifier
        self::assertSame(
            $femaleModifiersTable->getStrength(RaceCode::getIt($raceCode)),
            $femaleModifiersTable->getSize(RaceCode::getIt($raceCode))
        );
    }

    public function raceToSize(): array
    {
        return [
            [RaceCode::HUMAN, -1],
            [RaceCode::ELF, -1],
            [RaceCode::DWARF, 0],
            [RaceCode::HOBBIT, -1],
            [RaceCode::KROLL, -1],
            [RaceCode::ORC, -1],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Races\Exceptions\UnknownRace
     * @expectedExceptionMessageRegExp ~money money money~
     */
    public function I_can_not_get_property_for_unknown_race()
    {
        $femaleModifiersTable = new FemaleModifiersTable();
        $raceCode = $this->mockery(RaceCode::class);
        $raceCode->shouldReceive('getValue')->andReturn('tax_collector');
        $raceCode->shouldReceive('__toString')->andReturn('money money money');
        /** @var RaceCode $raceCode */
        $femaleModifiersTable->getStrength($raceCode);
    }
}