<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Weapons\Melee;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\SwordsTable;
use DrdPlus\Tests\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTableTest;

class SwordsTableTest extends MeleeWeaponsTableTest
{
    public function provideArmamentAndNameWithValue(): array
    {
        return [

            [MeleeWeaponCode::SHORT_SWORD, MeleeWeaponsTable::REQUIRED_STRENGTH, 2],
            [MeleeWeaponCode::SHORT_SWORD, MeleeWeaponsTable::LENGTH, 1],
            [MeleeWeaponCode::SHORT_SWORD, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::SHORT_SWORD, MeleeWeaponsTable::WOUNDS, 1],
            [MeleeWeaponCode::SHORT_SWORD, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::SHORT_SWORD, MeleeWeaponsTable::COVER, 3],
            [MeleeWeaponCode::SHORT_SWORD, MeleeWeaponsTable::WEIGHT, 1.3],
            [MeleeWeaponCode::SHORT_SWORD, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::HANGER, MeleeWeaponsTable::REQUIRED_STRENGTH, 4],
            [MeleeWeaponCode::HANGER, MeleeWeaponsTable::LENGTH, 1],
            [MeleeWeaponCode::HANGER, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::HANGER, MeleeWeaponsTable::WOUNDS, 3],
            [MeleeWeaponCode::HANGER, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::HANGER, MeleeWeaponsTable::COVER, 4],
            [MeleeWeaponCode::HANGER, MeleeWeaponsTable::WEIGHT, 1.5],
            [MeleeWeaponCode::HANGER, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::GLAIVE, MeleeWeaponsTable::REQUIRED_STRENGTH, 6],
            [MeleeWeaponCode::GLAIVE, MeleeWeaponsTable::LENGTH, 2],
            [MeleeWeaponCode::GLAIVE, MeleeWeaponsTable::OFFENSIVENESS, 4],
            [MeleeWeaponCode::GLAIVE, MeleeWeaponsTable::WOUNDS, 4],
            [MeleeWeaponCode::GLAIVE, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::GLAIVE, MeleeWeaponsTable::COVER, 4],
            [MeleeWeaponCode::GLAIVE, MeleeWeaponsTable::WEIGHT, 1.8],
            [MeleeWeaponCode::GLAIVE, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::LONG_SWORD, MeleeWeaponsTable::REQUIRED_STRENGTH, 7],
            [MeleeWeaponCode::LONG_SWORD, MeleeWeaponsTable::LENGTH, 3],
            [MeleeWeaponCode::LONG_SWORD, MeleeWeaponsTable::OFFENSIVENESS, 5],
            [MeleeWeaponCode::LONG_SWORD, MeleeWeaponsTable::WOUNDS, 3],
            [MeleeWeaponCode::LONG_SWORD, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::LONG_SWORD, MeleeWeaponsTable::COVER, 4],
            [MeleeWeaponCode::LONG_SWORD, MeleeWeaponsTable::WEIGHT, 2.0],
            [MeleeWeaponCode::LONG_SWORD, MeleeWeaponsTable::TWO_HANDED_ONLY, false],
            
            [MeleeWeaponCode::ONE_AND_HALF_HANDED_SWORD, MeleeWeaponsTable::REQUIRED_STRENGTH, 8],
            [MeleeWeaponCode::ONE_AND_HALF_HANDED_SWORD, MeleeWeaponsTable::LENGTH, 2],
            [MeleeWeaponCode::ONE_AND_HALF_HANDED_SWORD, MeleeWeaponsTable::OFFENSIVENESS, 5],
            [MeleeWeaponCode::ONE_AND_HALF_HANDED_SWORD, MeleeWeaponsTable::WOUNDS, 5],
            [MeleeWeaponCode::ONE_AND_HALF_HANDED_SWORD, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::ONE_AND_HALF_HANDED_SWORD, MeleeWeaponsTable::COVER, 5],
            [MeleeWeaponCode::ONE_AND_HALF_HANDED_SWORD, MeleeWeaponsTable::WEIGHT, 2.2],
            [MeleeWeaponCode::ONE_AND_HALF_HANDED_SWORD, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::BARBARIAN_SWORD, MeleeWeaponsTable::REQUIRED_STRENGTH, 10],
            [MeleeWeaponCode::BARBARIAN_SWORD, MeleeWeaponsTable::LENGTH, 2],
            [MeleeWeaponCode::BARBARIAN_SWORD, MeleeWeaponsTable::OFFENSIVENESS, 6],
            [MeleeWeaponCode::BARBARIAN_SWORD, MeleeWeaponsTable::WOUNDS, 6],
            [MeleeWeaponCode::BARBARIAN_SWORD, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::BARBARIAN_SWORD, MeleeWeaponsTable::COVER, 4],
            [MeleeWeaponCode::BARBARIAN_SWORD, MeleeWeaponsTable::WEIGHT, 2.5],
            [MeleeWeaponCode::BARBARIAN_SWORD, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::TWO_HANDED_SWORD, MeleeWeaponsTable::REQUIRED_STRENGTH, 12],
            [MeleeWeaponCode::TWO_HANDED_SWORD, MeleeWeaponsTable::LENGTH, 3],
            [MeleeWeaponCode::TWO_HANDED_SWORD, MeleeWeaponsTable::OFFENSIVENESS, 5],
            [MeleeWeaponCode::TWO_HANDED_SWORD, MeleeWeaponsTable::WOUNDS, 9],
            [MeleeWeaponCode::TWO_HANDED_SWORD, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::TWO_HANDED_SWORD, MeleeWeaponsTable::COVER, 4],
            [MeleeWeaponCode::TWO_HANDED_SWORD, MeleeWeaponsTable::WEIGHT, 4.0],
            [MeleeWeaponCode::TWO_HANDED_SWORD, MeleeWeaponsTable::TWO_HANDED_ONLY, false],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_every_weapon_by_weapon_codes_library()
    {
        $swordsTable = new SwordsTable();
        foreach (MeleeWeaponCode::getSwordsValues(false /* without custom ones */) as $swordValue) {
            $row = $swordsTable->getRow([$swordValue]);
            self::assertNotEmpty($row);
        }
    }

}
