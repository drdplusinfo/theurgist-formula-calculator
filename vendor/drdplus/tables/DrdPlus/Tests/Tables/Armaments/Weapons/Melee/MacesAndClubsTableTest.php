<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Weapons\Melee;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Tables\Armaments\Weapons\Melee\MacesAndClubsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTable;
use DrdPlus\Tests\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTableTest;

class MacesAndClubsTableTest extends MeleeWeaponsTableTest
{

    public function provideArmamentAndNameWithValue(): array
    {
        return [
            [MeleeWeaponCode::CUDGEL, MeleeWeaponsTable::REQUIRED_STRENGTH, 1],
            [MeleeWeaponCode::CUDGEL, MeleeWeaponsTable::LENGTH, 1],
            [MeleeWeaponCode::CUDGEL, MeleeWeaponsTable::OFFENSIVENESS, 1],
            [MeleeWeaponCode::CUDGEL, MeleeWeaponsTable::WOUNDS, 2],
            [MeleeWeaponCode::CUDGEL, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::CUDGEL, MeleeWeaponsTable::COVER, 1],
            [MeleeWeaponCode::CUDGEL, MeleeWeaponsTable::WEIGHT, 0.4],
            [MeleeWeaponCode::CUDGEL, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::CLUB, MeleeWeaponsTable::REQUIRED_STRENGTH, 3],
            [MeleeWeaponCode::CLUB, MeleeWeaponsTable::LENGTH, 1],
            [MeleeWeaponCode::CLUB, MeleeWeaponsTable::OFFENSIVENESS, 2],
            [MeleeWeaponCode::CLUB, MeleeWeaponsTable::WOUNDS, 3],
            [MeleeWeaponCode::CLUB, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::CLUB, MeleeWeaponsTable::COVER, 2],
            [MeleeWeaponCode::CLUB, MeleeWeaponsTable::WEIGHT, 1.0],
            [MeleeWeaponCode::CLUB, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::HOBNAILED_CLUB, MeleeWeaponsTable::REQUIRED_STRENGTH, 5],
            [MeleeWeaponCode::HOBNAILED_CLUB, MeleeWeaponsTable::LENGTH, 1],
            [MeleeWeaponCode::HOBNAILED_CLUB, MeleeWeaponsTable::OFFENSIVENESS, 2],
            [MeleeWeaponCode::HOBNAILED_CLUB, MeleeWeaponsTable::WOUNDS, 5],
            [MeleeWeaponCode::HOBNAILED_CLUB, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::HOBNAILED_CLUB, MeleeWeaponsTable::COVER, 2],
            [MeleeWeaponCode::HOBNAILED_CLUB, MeleeWeaponsTable::WEIGHT, 2.0],
            [MeleeWeaponCode::HOBNAILED_CLUB, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::LIGHT_MACE, MeleeWeaponsTable::REQUIRED_STRENGTH, 5],
            [MeleeWeaponCode::LIGHT_MACE, MeleeWeaponsTable::LENGTH, 2],
            [MeleeWeaponCode::LIGHT_MACE, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::LIGHT_MACE, MeleeWeaponsTable::WOUNDS, 4],
            [MeleeWeaponCode::LIGHT_MACE, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::LIGHT_MACE, MeleeWeaponsTable::COVER, 2],
            [MeleeWeaponCode::LIGHT_MACE, MeleeWeaponsTable::WEIGHT, 2.5],
            [MeleeWeaponCode::LIGHT_MACE, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::MACE, MeleeWeaponsTable::REQUIRED_STRENGTH, 8],
            [MeleeWeaponCode::MACE, MeleeWeaponsTable::LENGTH, 2],
            [MeleeWeaponCode::MACE, MeleeWeaponsTable::OFFENSIVENESS, 4],
            [MeleeWeaponCode::MACE, MeleeWeaponsTable::WOUNDS, 6],
            [MeleeWeaponCode::MACE, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::MACE, MeleeWeaponsTable::COVER, 3],
            [MeleeWeaponCode::MACE, MeleeWeaponsTable::WEIGHT, 4.0],
            [MeleeWeaponCode::MACE, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::HEAVY_CLUB, MeleeWeaponsTable::REQUIRED_STRENGTH, 8],
            [MeleeWeaponCode::HEAVY_CLUB, MeleeWeaponsTable::LENGTH, 2],
            [MeleeWeaponCode::HEAVY_CLUB, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::HEAVY_CLUB, MeleeWeaponsTable::WOUNDS, 7],
            [MeleeWeaponCode::HEAVY_CLUB, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::HEAVY_CLUB, MeleeWeaponsTable::COVER, 2],
            [MeleeWeaponCode::HEAVY_CLUB, MeleeWeaponsTable::WEIGHT, 3.5],
            [MeleeWeaponCode::HEAVY_CLUB, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::WAR_HAMMER, MeleeWeaponsTable::REQUIRED_STRENGTH, 10],
            [MeleeWeaponCode::WAR_HAMMER, MeleeWeaponsTable::LENGTH, 3],
            [MeleeWeaponCode::WAR_HAMMER, MeleeWeaponsTable::OFFENSIVENESS, 5],
            [MeleeWeaponCode::WAR_HAMMER, MeleeWeaponsTable::WOUNDS, 7],
            [MeleeWeaponCode::WAR_HAMMER, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::WAR_HAMMER, MeleeWeaponsTable::COVER, 3],
            [MeleeWeaponCode::WAR_HAMMER, MeleeWeaponsTable::WEIGHT, 4.5],
            [MeleeWeaponCode::WAR_HAMMER, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::TWO_HANDED_CLUB, MeleeWeaponsTable::REQUIRED_STRENGTH, 11],
            [MeleeWeaponCode::TWO_HANDED_CLUB, MeleeWeaponsTable::LENGTH, 2],
            [MeleeWeaponCode::TWO_HANDED_CLUB, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::TWO_HANDED_CLUB, MeleeWeaponsTable::WOUNDS, 10],
            [MeleeWeaponCode::TWO_HANDED_CLUB, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::TWO_HANDED_CLUB, MeleeWeaponsTable::COVER, 2],
            [MeleeWeaponCode::TWO_HANDED_CLUB, MeleeWeaponsTable::WEIGHT, 5.0],
            [MeleeWeaponCode::TWO_HANDED_CLUB, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::HEAVY_SLEDGEHAMMER, MeleeWeaponsTable::REQUIRED_STRENGTH, 13],
            [MeleeWeaponCode::HEAVY_SLEDGEHAMMER, MeleeWeaponsTable::LENGTH, 3],
            [MeleeWeaponCode::HEAVY_SLEDGEHAMMER, MeleeWeaponsTable::OFFENSIVENESS, 4],
            [MeleeWeaponCode::HEAVY_SLEDGEHAMMER, MeleeWeaponsTable::WOUNDS, 11],
            [MeleeWeaponCode::HEAVY_SLEDGEHAMMER, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CRUSH],
            [MeleeWeaponCode::HEAVY_SLEDGEHAMMER, MeleeWeaponsTable::COVER, 2],
            [MeleeWeaponCode::HEAVY_SLEDGEHAMMER, MeleeWeaponsTable::WEIGHT, 5.0],
            [MeleeWeaponCode::HEAVY_SLEDGEHAMMER, MeleeWeaponsTable::TWO_HANDED_ONLY, false],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_every_weapon_by_weapon_codes_library()
    {
        $macesAndClubsTable = new MacesAndClubsTable();
        foreach (MeleeWeaponCode::getMacesAndClubsValues(false /* without custom ones */) as $maceAndClubValue) {
            $row = $macesAndClubsTable->getRow([$maceAndClubValue]);
            self::assertNotEmpty($row);
        }
    }

}