<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Weapons\Melee;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\VoulgesAndTridentsTable;
use DrdPlus\Tests\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTableTest;

class VoulgesAndTridentsTableTest extends MeleeWeaponsTableTest
{
    public function provideArmamentAndNameWithValue(): array
    {
        return [
            [MeleeWeaponCode::PITCHFORK, MeleeWeaponsTable::REQUIRED_STRENGTH, 0],
            [MeleeWeaponCode::PITCHFORK, MeleeWeaponsTable::LENGTH, 3],
            [MeleeWeaponCode::PITCHFORK, MeleeWeaponsTable::OFFENSIVENESS, 2],
            [MeleeWeaponCode::PITCHFORK, MeleeWeaponsTable::WOUNDS, 2],
            [MeleeWeaponCode::PITCHFORK, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [MeleeWeaponCode::PITCHFORK, MeleeWeaponsTable::COVER, 3],
            [MeleeWeaponCode::PITCHFORK, MeleeWeaponsTable::WEIGHT, 1.5],
            [MeleeWeaponCode::PITCHFORK, MeleeWeaponsTable::TWO_HANDED_ONLY, true],

            [MeleeWeaponCode::LIGHT_VOULGE, MeleeWeaponsTable::REQUIRED_STRENGTH, 2],
            [MeleeWeaponCode::LIGHT_VOULGE, MeleeWeaponsTable::LENGTH, 4],
            [MeleeWeaponCode::LIGHT_VOULGE, MeleeWeaponsTable::OFFENSIVENESS, 4],
            [MeleeWeaponCode::LIGHT_VOULGE, MeleeWeaponsTable::WOUNDS, 4],
            [MeleeWeaponCode::LIGHT_VOULGE, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::LIGHT_VOULGE, MeleeWeaponsTable::COVER, 3],
            [MeleeWeaponCode::LIGHT_VOULGE, MeleeWeaponsTable::WEIGHT, 2.0],
            [MeleeWeaponCode::LIGHT_VOULGE, MeleeWeaponsTable::TWO_HANDED_ONLY, true],

            [MeleeWeaponCode::LIGHT_TRIDENT, MeleeWeaponsTable::REQUIRED_STRENGTH, 5],
            [MeleeWeaponCode::LIGHT_TRIDENT, MeleeWeaponsTable::LENGTH, 4],
            [MeleeWeaponCode::LIGHT_TRIDENT, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::LIGHT_TRIDENT, MeleeWeaponsTable::WOUNDS, 6],
            [MeleeWeaponCode::LIGHT_TRIDENT, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [MeleeWeaponCode::LIGHT_TRIDENT, MeleeWeaponsTable::COVER, 4],
            [MeleeWeaponCode::LIGHT_TRIDENT, MeleeWeaponsTable::WEIGHT, 2.5],
            [MeleeWeaponCode::LIGHT_TRIDENT, MeleeWeaponsTable::TWO_HANDED_ONLY, true],

            [MeleeWeaponCode::HALBERD, MeleeWeaponsTable::REQUIRED_STRENGTH, 6],
            [MeleeWeaponCode::HALBERD, MeleeWeaponsTable::LENGTH, 4],
            [MeleeWeaponCode::HALBERD, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::HALBERD, MeleeWeaponsTable::WOUNDS, 7],
            [MeleeWeaponCode::HALBERD, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::HALBERD, MeleeWeaponsTable::COVER, 3],
            [MeleeWeaponCode::HALBERD, MeleeWeaponsTable::WEIGHT, 3.5],
            [MeleeWeaponCode::HALBERD, MeleeWeaponsTable::TWO_HANDED_ONLY, true],

            [MeleeWeaponCode::HEAVY_VOULGE, MeleeWeaponsTable::REQUIRED_STRENGTH, 7],
            [MeleeWeaponCode::HEAVY_VOULGE, MeleeWeaponsTable::LENGTH, 4],
            [MeleeWeaponCode::HEAVY_VOULGE, MeleeWeaponsTable::OFFENSIVENESS, 4],
            [MeleeWeaponCode::HEAVY_VOULGE, MeleeWeaponsTable::WOUNDS, 9],
            [MeleeWeaponCode::HEAVY_VOULGE, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::HEAVY_VOULGE, MeleeWeaponsTable::COVER, 3],
            [MeleeWeaponCode::HEAVY_VOULGE, MeleeWeaponsTable::WEIGHT, 4.0],
            [MeleeWeaponCode::HEAVY_VOULGE, MeleeWeaponsTable::TWO_HANDED_ONLY, true],

            [MeleeWeaponCode::HEAVY_TRIDENT, MeleeWeaponsTable::REQUIRED_STRENGTH, 9],
            [MeleeWeaponCode::HEAVY_TRIDENT, MeleeWeaponsTable::LENGTH, 4],
            [MeleeWeaponCode::HEAVY_TRIDENT, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::HEAVY_TRIDENT, MeleeWeaponsTable::WOUNDS, 10],
            [MeleeWeaponCode::HEAVY_TRIDENT, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [MeleeWeaponCode::HEAVY_TRIDENT, MeleeWeaponsTable::COVER, 4],
            [MeleeWeaponCode::HEAVY_TRIDENT, MeleeWeaponsTable::WEIGHT, 4.0],
            [MeleeWeaponCode::HEAVY_TRIDENT, MeleeWeaponsTable::TWO_HANDED_ONLY, true],

            [MeleeWeaponCode::HEAVY_HALBERD, MeleeWeaponsTable::REQUIRED_STRENGTH, 10],
            [MeleeWeaponCode::HEAVY_HALBERD, MeleeWeaponsTable::LENGTH, 4],
            [MeleeWeaponCode::HEAVY_HALBERD, MeleeWeaponsTable::OFFENSIVENESS, 3],
            [MeleeWeaponCode::HEAVY_HALBERD, MeleeWeaponsTable::WOUNDS, 12],
            [MeleeWeaponCode::HEAVY_HALBERD, MeleeWeaponsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::CUT],
            [MeleeWeaponCode::HEAVY_HALBERD, MeleeWeaponsTable::COVER, 3],
            [MeleeWeaponCode::HEAVY_HALBERD, MeleeWeaponsTable::WEIGHT, 6.0],
            [MeleeWeaponCode::HEAVY_HALBERD, MeleeWeaponsTable::TWO_HANDED_ONLY, true],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_every_weapon_by_weapon_codes_library()
    {
        $voulgesAndTridentsTable = new VoulgesAndTridentsTable();
        foreach (MeleeWeaponCode::getVoulgesAndTridentsValues(false /* without custom ones */) as $voulgeAndTridentValue) {
            $row = $voulgesAndTridentsTable->getRow([$voulgeAndTridentValue]);
            self::assertNotEmpty($row);
        }
    }

}