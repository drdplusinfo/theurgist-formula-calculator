<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Weapons\Melee;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\UnarmedTable;
use DrdPlus\Tests\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTableTest;

class UnarmedTableTest extends MeleeWeaponsTableTest
{
    public function provideArmamentAndNameWithValue(): array
    {
        return [
            [MeleeWeaponCode::HAND, MeleeWeaponsTable::REQUIRED_STRENGTH, false],
            [MeleeWeaponCode::HAND, MeleeWeaponsTable::LENGTH, 0],
            [MeleeWeaponCode::HAND, MeleeWeaponsTable::OFFENSIVENESS, 0],
            [MeleeWeaponCode::HAND, MeleeWeaponsTable::WOUNDS, -2],
            [MeleeWeaponCode::HAND, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::HAND, MeleeWeaponsTable::COVER, 0],
            [MeleeWeaponCode::HAND, MeleeWeaponsTable::WEIGHT, 0.0],
            [MeleeWeaponCode::HAND, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::HOBNAILED_GLOVE, MeleeWeaponsTable::REQUIRED_STRENGTH, -4],
            [MeleeWeaponCode::HOBNAILED_GLOVE, MeleeWeaponsTable::LENGTH, 0],
            [MeleeWeaponCode::HOBNAILED_GLOVE, MeleeWeaponsTable::OFFENSIVENESS, 0],
            [MeleeWeaponCode::HOBNAILED_GLOVE, MeleeWeaponsTable::WOUNDS, 0],
            [MeleeWeaponCode::HOBNAILED_GLOVE, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::HOBNAILED_GLOVE, MeleeWeaponsTable::COVER, 0],
            [MeleeWeaponCode::HOBNAILED_GLOVE, MeleeWeaponsTable::WEIGHT, 0.2],
            [MeleeWeaponCode::HOBNAILED_GLOVE, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::LEG, MeleeWeaponsTable::REQUIRED_STRENGTH, -5],
            [MeleeWeaponCode::LEG, MeleeWeaponsTable::LENGTH, 0],
            [MeleeWeaponCode::LEG, MeleeWeaponsTable::OFFENSIVENESS, -1],
            [MeleeWeaponCode::LEG, MeleeWeaponsTable::WOUNDS, 1],
            [MeleeWeaponCode::LEG, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::LEG, MeleeWeaponsTable::COVER, 0],
            [MeleeWeaponCode::LEG, MeleeWeaponsTable::WEIGHT, 0.0],
            [MeleeWeaponCode::LEG, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::HOBNAILED_BOOT, MeleeWeaponsTable::REQUIRED_STRENGTH, -4],
            [MeleeWeaponCode::HOBNAILED_BOOT, MeleeWeaponsTable::LENGTH, 0],
            [MeleeWeaponCode::HOBNAILED_BOOT, MeleeWeaponsTable::OFFENSIVENESS, -2],
            [MeleeWeaponCode::HOBNAILED_BOOT, MeleeWeaponsTable::WOUNDS, 4],
            [MeleeWeaponCode::HOBNAILED_BOOT, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::HOBNAILED_BOOT, MeleeWeaponsTable::COVER, 0],
            [MeleeWeaponCode::HOBNAILED_BOOT, MeleeWeaponsTable::WEIGHT, 0.4],
            [MeleeWeaponCode::HOBNAILED_BOOT, MeleeWeaponsTable::TWO_HANDED_ONLY, false],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_every_weapon_by_weapon_codes_library()
    {
        $unarmedTable = new UnarmedTable();
        foreach (MeleeWeaponCode::getUnarmedValues(false /* without custom ones */) as $unarmedValue) {
            $row = $unarmedTable->getRow([$unarmedValue]);
            self::assertNotEmpty($row);
        }
    }

}