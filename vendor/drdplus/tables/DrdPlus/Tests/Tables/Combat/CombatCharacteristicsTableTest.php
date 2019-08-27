<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Combat;

use DrdPlus\Codes\CombatCharacteristicCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Combat\CombatCharacteristicsTable;
use DrdPlus\Tests\Tables\TableTest;

class CombatCharacteristicsTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        self::assertSame(
            [['characteristic', 'property', 'divide_by', 'round_up', 'round_down']],
            (new CombatCharacteristicsTable())->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_its_indexed_values()
    {
        self::assertSame(
            [
                CombatCharacteristicCode::ATTACK => [CombatCharacteristicsTable::PROPERTY => PropertyCode::AGILITY, CombatCharacteristicsTable::DIVIDE_BY => 2, CombatCharacteristicsTable::ROUND_UP => false, CombatCharacteristicsTable::ROUND_DOWN => true],
                CombatCharacteristicCode::DEFENSE => [CombatCharacteristicsTable::PROPERTY => PropertyCode::AGILITY, CombatCharacteristicsTable::DIVIDE_BY => 2, CombatCharacteristicsTable::ROUND_UP => true, CombatCharacteristicsTable::ROUND_DOWN => false],
                CombatCharacteristicCode::SHOOTING => [CombatCharacteristicsTable::PROPERTY => PropertyCode::KNACK, CombatCharacteristicsTable::DIVIDE_BY => 2, CombatCharacteristicsTable::ROUND_UP => false, CombatCharacteristicsTable::ROUND_DOWN => true],
            ],
            (new CombatCharacteristicsTable())->getIndexedValues()
        );
    }
}