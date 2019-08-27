<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Combat;

use DrdPlus\Codes\ProfessionCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Combat\FightTable;
use DrdPlus\Tests\Tables\TableTest;

class FightTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        self::assertSame([['profession', 'first_property', 'second_property']], (new FightTable())->getHeader());
    }

    /**
     * @test
     */
    public function I_can_get_its_indexed_values()
    {
        self::assertSame(
            [
                ProfessionCode::COMMONER => [FightTable::FIRST_PROPERTY => false, FightTable::SECOND_PROPERTY => PropertyCode::AGILITY],
                ProfessionCode::FIGHTER => [FightTable::FIRST_PROPERTY => PropertyCode::AGILITY, FightTable::SECOND_PROPERTY => false],
                ProfessionCode::THIEF => [FightTable::FIRST_PROPERTY => PropertyCode::KNACK, FightTable::SECOND_PROPERTY => PropertyCode::AGILITY],
                ProfessionCode::RANGER => [FightTable::FIRST_PROPERTY => PropertyCode::KNACK, FightTable::SECOND_PROPERTY => PropertyCode::AGILITY],
                ProfessionCode::WIZARD => [FightTable::FIRST_PROPERTY => PropertyCode::INTELLIGENCE, FightTable::SECOND_PROPERTY => PropertyCode::AGILITY],
                ProfessionCode::THEURGIST => [FightTable::FIRST_PROPERTY => PropertyCode::INTELLIGENCE, FightTable::SECOND_PROPERTY => PropertyCode::AGILITY],
                ProfessionCode::PRIEST => [FightTable::FIRST_PROPERTY => PropertyCode::CHARISMA, FightTable::SECOND_PROPERTY => PropertyCode::AGILITY],
            ],
            (new FightTable())->getIndexedValues()
        );
    }
}