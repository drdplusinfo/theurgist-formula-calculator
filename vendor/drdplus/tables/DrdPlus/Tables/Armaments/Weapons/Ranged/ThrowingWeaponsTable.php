<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Weapons\Ranged;

use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Armaments\Weapons\Ranged\Partials\RangedWeaponsTable;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Weight\Weight;

/**
 * See PPH page 88 right column, @link https://pph.drdplus.info/#tabulka_strelnych_a_vrhacich_zbrani
 */
class ThrowingWeaponsTable extends RangedWeaponsTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/throwing_weapons.csv';
    }

    /**
     * @param RangedWeaponCode $throwingWeaponCode you need a code even for a custom weapon, so prove now
     * @param Strength $requiredStrength
     * @param DistanceBonus $range
     * @param int $offensiveness
     * @param int $wounds
     * @param PhysicalWoundTypeCode $woundTypeCode
     * @param int $cover
     * @param Weight $weight
     * @param bool $twoHandedOnly
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\NewWeaponIsNotOfRequiredType
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\DifferentWeaponIsUnderSameName
     */
    public function addNewThrowingWeapon(
        RangedWeaponCode $throwingWeaponCode,
        Strength $requiredStrength,
        DistanceBonus $range,
        int $offensiveness,
        int $wounds,
        PhysicalWoundTypeCode $woundTypeCode,
        int $cover,
        Weight $weight,
        bool $twoHandedOnly
    ): bool
    {
        return $this->addCustomRangedWeapon(
            $throwingWeaponCode,
            WeaponCategoryCode::getIt(WeaponCategoryCode::THROWING_WEAPONS),
            $requiredStrength,
            $range,
            $offensiveness,
            $wounds,
            $woundTypeCode,
            $cover,
            $weight,
            $twoHandedOnly,
            [] // no custom parameters
        );
    }
}