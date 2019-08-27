<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Partials;

use DrdPlus\Tables\Armaments\Weapons\Melee\MeleeWeaponStrengthSanctionsTable;

abstract class AbstractMeleeWeaponlikeStrengthSanctionsTableTest extends AbstractStrengthSanctionsTableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [['missing_strength', 'fight_number', 'attack_number', 'defense_number', 'base_of_wounds', 'can_use_armament']],
            (new MeleeWeaponStrengthSanctionsTable())->getHeader()
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
                    'fight_number' => 0,
                    'attack_number' => 0,
                    'defense_number' => 0,
                    'base_of_wounds' => 0,
                    'can_use_armament' => true,
                ],
                1 => [
                    'missing_strength' => 1,
                    'fight_number' => -1,
                    'attack_number' => 0,
                    'defense_number' => 0,
                    'base_of_wounds' => 0,
                    'can_use_armament' => true,
                ],
                2 => [
                    'missing_strength' => 2,
                    'fight_number' => -1,
                    'attack_number' => -1,
                    'defense_number' => 0,
                    'base_of_wounds' => 0,
                    'can_use_armament' => true,
                ],
                3 => [
                    'missing_strength' => 3,
                    'fight_number' => -2,
                    'attack_number' => -1,
                    'defense_number' => -1,
                    'base_of_wounds' => 0,
                    'can_use_armament' => true,
                ],
                4 => [
                    'missing_strength' => 4,
                    'fight_number' => -2,
                    'attack_number' => -2,
                    'defense_number' => -1,
                    'base_of_wounds' => -1,
                    'can_use_armament' => true,
                ],
                5 => [
                    'missing_strength' => 5,
                    'fight_number' => -3,
                    'attack_number' => -2,
                    'defense_number' => -2,
                    'base_of_wounds' => -1,
                    'can_use_armament' => true,
                ],
                6 => [
                    'missing_strength' => 6,
                    'fight_number' => -3,
                    'attack_number' => -3,
                    'defense_number' => -2,
                    'base_of_wounds' => -2,
                    'can_use_armament' => true,
                ],
                7 => [
                    'missing_strength' => 7,
                    'fight_number' => -4,
                    'attack_number' => -3,
                    'defense_number' => -3,
                    'base_of_wounds' => -2,
                    'can_use_armament' => true,
                ],
                8 => [
                    'missing_strength' => 8,
                    'fight_number' => -4,
                    'attack_number' => -4,
                    'defense_number' => -3,
                    'base_of_wounds' => -3,
                    'can_use_armament' => true,
                ],
                9 => [
                    'missing_strength' => 9,
                    'fight_number' => -5,
                    'attack_number' => -4,
                    'defense_number' => -4,
                    'base_of_wounds' => -3,
                    'can_use_armament' => true,
                ],
                10 => [
                    'missing_strength' => 10,
                    'fight_number' => -5,
                    'attack_number' => -5,
                    'defense_number' => -4,
                    'base_of_wounds' => -4,
                    'can_use_armament' => true,
                ],
                11 => [
                    'missing_strength' => 11,
                    'fight_number' => false,
                    'attack_number' => false,
                    'defense_number' => false,
                    'base_of_wounds' => false,
                    'can_use_armament' => false,
                ],
            ],
            (new MeleeWeaponStrengthSanctionsTable())->getIndexedValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_sanctions_for_missing_strength()
    {
        self::assertSame(
            [
                'missing_strength' => 2,
                'fight_number' => -1,
                'attack_number' => -1,
                'defense_number' => 0,
                'base_of_wounds' => 0,
                'can_use_armament' => true,
            ],
            (new MeleeWeaponStrengthSanctionsTable())->getSanctionsForMissingStrength(2)
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
        self::assertSame(
            $expectedValues,
            (new MeleeWeaponStrengthSanctionsTable())->getSanctionsForMissingStrength($missingStrength),
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
                    MeleeWeaponStrengthSanctionsTable::MISSING_STRENGTH => 0,
                    MeleeWeaponStrengthSanctionsTable::FIGHT_NUMBER => 0,
                    MeleeWeaponStrengthSanctionsTable::ATTACK_NUMBER => 0,
                    MeleeWeaponStrengthSanctionsTable::DEFENSE_NUMBER => 0,
                    MeleeWeaponStrengthSanctionsTable::BASE_OF_WOUNDS => 0,
                    MeleeWeaponStrengthSanctionsTable::CAN_USE_ARMAMENT => true,
                ]
            ];
        }
        for ($missingStrength = 1; $missingStrength <= 10; $missingStrength++) {
            $values[] = [
                $missingStrength,
                [
                    MeleeWeaponStrengthSanctionsTable::MISSING_STRENGTH => $missingStrength,
                    MeleeWeaponStrengthSanctionsTable::FIGHT_NUMBER => (int)floor(-$missingStrength / 2),
                    MeleeWeaponStrengthSanctionsTable::ATTACK_NUMBER => min(0, (int)floor((-$missingStrength + 1) / 2)),
                    MeleeWeaponStrengthSanctionsTable::DEFENSE_NUMBER => min(0, (int)floor((-$missingStrength + 2) / 2)),
                    MeleeWeaponStrengthSanctionsTable::BASE_OF_WOUNDS => min(0, (int)floor((-$missingStrength + 3) / 2)),
                    MeleeWeaponStrengthSanctionsTable::CAN_USE_ARMAMENT => true,
                ]
            ];
        }
        for ($missingStrength = 11; $missingStrength <= 20; $missingStrength++) {
            $values[] = [
                $missingStrength,
                [
                    MeleeWeaponStrengthSanctionsTable::MISSING_STRENGTH => 11,
                    MeleeWeaponStrengthSanctionsTable::FIGHT_NUMBER => false,
                    MeleeWeaponStrengthSanctionsTable::ATTACK_NUMBER => false,
                    MeleeWeaponStrengthSanctionsTable::DEFENSE_NUMBER => false,
                    MeleeWeaponStrengthSanctionsTable::BASE_OF_WOUNDS => false,
                    MeleeWeaponStrengthSanctionsTable::CAN_USE_ARMAMENT => false,
                ]
            ];
        }

        return $values;
    }

    /**
     * @test
     * @dataProvider provideSanctionName
     * @param string $sanctionName
     */
    public function I_get_always_zero_for_every_sanction_if_no_missing_strength($sanctionName)
    {
        $sanctionGetter = 'get' . ucfirst($sanctionName) . 'Sanction';
        $meleeWeaponSanctionsTable = new MeleeWeaponStrengthSanctionsTable();
        self::assertSame(0, $meleeWeaponSanctionsTable->$sanctionGetter(0));
        self::assertSame(0, $meleeWeaponSanctionsTable->$sanctionGetter(-1));
        self::assertSame(0, $meleeWeaponSanctionsTable->$sanctionGetter(-10));
    }

    public function provideSanctionName()
    {
        return [
            ['fightNumber'],
            ['attackNumber'],
            ['defenseNumber'],
            ['baseOfWounds'],
        ];
    }

    /**
     * @test
     * @dataProvider provideSanctionName
     * @param string $sanctionName
     */
    public function I_can_not_get_any_sanction_for_too_much_missing_strength($sanctionName)
    {
        $this->expectException(\DrdPlus\Tables\Armaments\Weapons\Exceptions\CanNotUseWeaponBecauseOfMissingStrength::class);
        $sanctionGetter = 'get' . ucfirst($sanctionName) . 'Sanction';
        (new MeleeWeaponStrengthSanctionsTable())->$sanctionGetter(11);
    }
}