<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Weapons\Melee;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Tables\Armaments\Weapons\Melee\KnivesAndDaggersTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTable;
use DrdPlus\Tests\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTableTest;

class KnivesAndDaggersTableTest extends MeleeWeaponsTableTest
{
    public function provideArmamentAndNameWithValue(): array
    {
        return [
            [MeleeWeaponCode::KNIFE, MeleeWeaponsTable::REQUIRED_STRENGTH, -3],
            [MeleeWeaponCode::KNIFE, MeleeWeaponsTable::LENGTH, 0],
            [MeleeWeaponCode::KNIFE, MeleeWeaponsTable::OFFENSIVENESS, 0],
            [MeleeWeaponCode::KNIFE, MeleeWeaponsTable::WOUNDS, -2],
            [MeleeWeaponCode::KNIFE, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [MeleeWeaponCode::KNIFE, MeleeWeaponsTable::COVER, 1],
            [MeleeWeaponCode::KNIFE, MeleeWeaponsTable::WEIGHT, 0.2],
            [MeleeWeaponCode::KNIFE, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::DAGGER, MeleeWeaponsTable::REQUIRED_STRENGTH, -1],
            [MeleeWeaponCode::DAGGER, MeleeWeaponsTable::LENGTH, 0],
            [MeleeWeaponCode::DAGGER, MeleeWeaponsTable::OFFENSIVENESS, 1],
            [MeleeWeaponCode::DAGGER, MeleeWeaponsTable::WOUNDS, 1],
            [MeleeWeaponCode::DAGGER, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [MeleeWeaponCode::DAGGER, MeleeWeaponsTable::COVER, 2],
            [MeleeWeaponCode::DAGGER, MeleeWeaponsTable::WEIGHT, 0.2],
            [MeleeWeaponCode::DAGGER, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::STABBING_DAGGER, MeleeWeaponsTable::REQUIRED_STRENGTH, -1],
            [MeleeWeaponCode::STABBING_DAGGER, MeleeWeaponsTable::LENGTH, 0],
            [MeleeWeaponCode::STABBING_DAGGER, MeleeWeaponsTable::OFFENSIVENESS, 2],
            [MeleeWeaponCode::STABBING_DAGGER, MeleeWeaponsTable::WOUNDS, 0],
            [MeleeWeaponCode::STABBING_DAGGER, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [MeleeWeaponCode::STABBING_DAGGER, MeleeWeaponsTable::COVER, 1],
            [MeleeWeaponCode::STABBING_DAGGER, MeleeWeaponsTable::WEIGHT, 0.2],
            [MeleeWeaponCode::STABBING_DAGGER, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::LONG_KNIFE, MeleeWeaponsTable::REQUIRED_STRENGTH, -2],
            [MeleeWeaponCode::LONG_KNIFE, MeleeWeaponsTable::LENGTH, 1],
            [MeleeWeaponCode::LONG_KNIFE, MeleeWeaponsTable::OFFENSIVENESS, 1],
            [MeleeWeaponCode::LONG_KNIFE, MeleeWeaponsTable::WOUNDS, -1],
            [MeleeWeaponCode::LONG_KNIFE, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [MeleeWeaponCode::LONG_KNIFE, MeleeWeaponsTable::COVER, 1],
            [MeleeWeaponCode::LONG_KNIFE, MeleeWeaponsTable::WEIGHT, 0.2],
            [MeleeWeaponCode::LONG_KNIFE, MeleeWeaponsTable::TWO_HANDED_ONLY, false],

            [MeleeWeaponCode::LONG_DAGGER, MeleeWeaponsTable::REQUIRED_STRENGTH, 1],
            [MeleeWeaponCode::LONG_DAGGER, MeleeWeaponsTable::LENGTH, 1],
            [MeleeWeaponCode::LONG_DAGGER, MeleeWeaponsTable::OFFENSIVENESS, 1],
            [MeleeWeaponCode::LONG_DAGGER, MeleeWeaponsTable::WOUNDS, 2],
            [MeleeWeaponCode::LONG_DAGGER, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [MeleeWeaponCode::LONG_DAGGER, MeleeWeaponsTable::COVER, 2],
            [MeleeWeaponCode::LONG_DAGGER, MeleeWeaponsTable::WEIGHT, 0.3],
            [MeleeWeaponCode::LONG_DAGGER, MeleeWeaponsTable::TWO_HANDED_ONLY, false],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_every_weapon_by_weapon_codes_library()
    {
        $knivesAndDaggersTable = new KnivesAndDaggersTable();
        foreach (MeleeWeaponCode::getKnivesAndDaggersValues(false /* without custom ones */) as $knifeAndDaggerValue) {
            $row = $knivesAndDaggersTable->getRow([$knifeAndDaggerValue]);
            self::assertNotEmpty($row);
        }
    }

}