<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Armors;

use DrdPlus\Tables\Armaments\Armors\ArmorStrengthSanctionsTable;
use DrdPlus\Tests\Tables\Armaments\Partials\AbstractStrengthSanctionsTableTest;

class ArmorStrengthSanctionsTableTest extends AbstractStrengthSanctionsTableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        $armorSanctionsTable = new ArmorStrengthSanctionsTable();
        self::assertSame(
            [['missing_strength', 'sanction_description', 'agility_sanction', 'can_move']],
            $armorSanctionsTable->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        self::assertSame(
            [
                0 => [
                    'missing_strength' => 0,
                    'sanction_description' => 'light',
                    'agility_sanction' => 0,
                    'can_move' => true
                ],
                3 => [
                    'missing_strength' => 3,
                    'sanction_description' => 'medium',
                    'agility_sanction' => -2,
                    'can_move' => true
                ],
                6 => [
                    'missing_strength' => 6,
                    'sanction_description' => 'heavy',
                    'agility_sanction' => -4,
                    'can_move' => true
                ],
                8 => [
                    'missing_strength' => 8,
                    'sanction_description' => 'very_heavy',
                    'agility_sanction' => -8,
                    'can_move' => true
                ],
                10 => [
                    'missing_strength' => 10,
                    'sanction_description' => 'extreme',
                    'agility_sanction' => -12,
                    'can_move' => true
                ],
                11 => [
                    'missing_strength' => 11,
                    'sanction_description' => 'unbearable',
                    'agility_sanction' => false,
                    'can_move' => false
                ],
            ],
            (new ArmorStrengthSanctionsTable())->getIndexedValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_sanctions_for_missing_strength()
    {
        self::assertSame(
            [
                'missing_strength' => 10,
                'sanction_description' => 'extreme',
                'agility_sanction' => -12,
                'can_move' => true
            ],
            (new ArmorStrengthSanctionsTable())->getSanctionsForMissingStrength(9)
        );
    }

    /**
     * @test
     * @dataProvider provideMissingStrengthAndResult
     * @param bool|int missingStrength
     * @param array $expectedValues
     */
    public function I_can_get_sanction_data_for_any_strength_missing($missingStrength, array $expectedValues)
    {
        $armorSanctionsTable = new ArmorStrengthSanctionsTable();
        self::assertSame(
            $expectedValues,
            $armorSanctionsTable->getSanctionsForMissingStrength($missingStrength),
            'Expected ' . serialize($expectedValues) . " for missing strength $missingStrength"
        );
    }

    public function provideMissingStrengthAndResult()
    {
        $values = [];
        for ($missingStrength = -5; $missingStrength <= 0; $missingStrength++) {
            $values[] = [
                $missingStrength,
                [
                    ArmorStrengthSanctionsTable::MISSING_STRENGTH => 0,
                    ArmorStrengthSanctionsTable::SANCTION_DESCRIPTION => 'light',
                    ArmorStrengthSanctionsTable::AGILITY_SANCTION => 0,
                    ArmorStrengthSanctionsTable::CAN_MOVE => true,
                ]
            ];
        }
        for ($missingStrength = 1; $missingStrength <= 3; $missingStrength++) {
            $values[] = [
                $missingStrength,
                [
                    ArmorStrengthSanctionsTable::MISSING_STRENGTH => 3,
                    ArmorStrengthSanctionsTable::SANCTION_DESCRIPTION => 'medium',
                    ArmorStrengthSanctionsTable::AGILITY_SANCTION => -2,
                    ArmorStrengthSanctionsTable::CAN_MOVE => true,
                ]
            ];
        }
        for ($missingStrength = 4; $missingStrength <= 6; $missingStrength++) {
            $values[] = [
                $missingStrength,
                [
                    ArmorStrengthSanctionsTable::MISSING_STRENGTH => 6,
                    ArmorStrengthSanctionsTable::SANCTION_DESCRIPTION => 'heavy',
                    ArmorStrengthSanctionsTable::AGILITY_SANCTION => -4,
                    ArmorStrengthSanctionsTable::CAN_MOVE => true,
                ]
            ];
        }
        for ($missingStrength = 7; $missingStrength <= 8; $missingStrength++) {
            $values[] = [
                $missingStrength,
                [
                    ArmorStrengthSanctionsTable::MISSING_STRENGTH => 8,
                    ArmorStrengthSanctionsTable::SANCTION_DESCRIPTION => 'very_heavy',
                    ArmorStrengthSanctionsTable::AGILITY_SANCTION => -8,
                    ArmorStrengthSanctionsTable::CAN_MOVE => true,
                ]
            ];
        }
        for ($missingStrength = 9; $missingStrength <= 10; $missingStrength++) {
            $values[] = [
                $missingStrength,
                [
                    ArmorStrengthSanctionsTable::MISSING_STRENGTH => 10,
                    ArmorStrengthSanctionsTable::SANCTION_DESCRIPTION => 'extreme',
                    ArmorStrengthSanctionsTable::AGILITY_SANCTION => -12,
                    ArmorStrengthSanctionsTable::CAN_MOVE => true,
                ]
            ];
        }
        for ($missingStrength = 11; $missingStrength <= 20; $missingStrength++) {
            $values[] = [
                $missingStrength,
                [
                    ArmorStrengthSanctionsTable::MISSING_STRENGTH => 11,
                    ArmorStrengthSanctionsTable::SANCTION_DESCRIPTION => 'unbearable',
                    ArmorStrengthSanctionsTable::AGILITY_SANCTION => false,
                    ArmorStrengthSanctionsTable::CAN_MOVE => false,
                ]
            ];
        }

        return $values;
    }

    /**
     * @test
     */
    public function I_can_find_out_if_can_move()
    {
        $armorSanctionsTable = new ArmorStrengthSanctionsTable();
        self::assertTrue($armorSanctionsTable->canMove(-10));
        self::assertTrue($armorSanctionsTable->canMove(10));
        self::assertFalse($armorSanctionsTable->canMove(11));
        self::assertFalse($armorSanctionsTable->canMove(100));
    }

    /**
     * @test
     */
    public function I_can_get_sanction_description()
    {
        $armorSanctionsTable = new ArmorStrengthSanctionsTable();
        self::assertSame('light', $armorSanctionsTable->getSanctionDescription(-10));
        self::assertSame('extreme', $armorSanctionsTable->getSanctionDescription(10));
        self::assertSame('unbearable', $armorSanctionsTable->getSanctionDescription(999));
    }

    /**
     * @test
     */
    public function I_can_get_agility_malus()
    {
        $armorSanctionsTable = new ArmorStrengthSanctionsTable();
        self::assertSame(0, $armorSanctionsTable->getAgilityMalus(-10));
        self::assertSame(-8, $armorSanctionsTable->getAgilityMalus(7));
        self::assertSame(-8, $armorSanctionsTable->getAgilityMalus(8));
        self::assertSame(-12, $armorSanctionsTable->getAgilityMalus(9));
    }

    /**
     * @test
     */
    public function I_can_not_get_agility_malus_if_unbearable()
    {
        $this->expectException(\DrdPlus\Tables\Armaments\Exceptions\CanNotUseArmorBecauseOfMissingStrength::class);
        $armorSanctionsTable = new ArmorStrengthSanctionsTable();
        self::assertSame(0, $armorSanctionsTable->getAgilityMalus(11));
    }
}