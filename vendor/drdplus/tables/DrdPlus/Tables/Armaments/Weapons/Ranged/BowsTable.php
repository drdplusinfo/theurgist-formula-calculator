<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Weapons\Ranged;

use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon;
use DrdPlus\Tables\Armaments\Weapons\Ranged\Partials\RangedWeaponsTable;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Weight\Weight;
use Granam\String\StringInterface;

/**
 * See PPH page 88 right column, @link https://pph.drdplus.info/#tabulka_strelnych_a_vrhacich_zbrani
 */
class BowsTable extends RangedWeaponsTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/bows.csv';
    }

    public const MAXIMAL_APPLICABLE_STRENGTH = 'maximal_applicable_strength';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REQUIRED_STRENGTH => self::INTEGER,
            self::MAXIMAL_APPLICABLE_STRENGTH => self::POSITIVE_INTEGER,
            self::OFFENSIVENESS => self::INTEGER,
            self::WOUNDS => self::INTEGER,
            self::WOUNDS_TYPE => self::STRING,
            self::RANGE => self::POSITIVE_INTEGER,
            self::COVER => self::POSITIVE_INTEGER,
            self::WEIGHT => self::FLOAT,
            self::TWO_HANDED_ONLY => self::BOOLEAN,
        ];
    }

    /**
     * @param string|StringInterface|RangedWeaponCode $bowCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Weapons\Ranged\Exceptions\UnknownBow
     */
    public function getMaximalApplicableStrengthOf($bowCode): int
    {
        try {
            return $this->getValueOf($bowCode, self::MAXIMAL_APPLICABLE_STRENGTH);
        } catch (UnknownRangedWeapon $unknownRangedWeapon) {
            throw new Exceptions\UnknownBow("Unknown bow '{$bowCode}'");
        }
    }

    /**
     * @param RangedWeaponCode $bowCode you need a code even for a custom weapon, so prove now
     * @param Strength $requiredStrength
     * @param DistanceBonus $range
     * @param int $offensiveness
     * @param int $wounds
     * @param PhysicalWoundTypeCode $woundTypeCode
     * @param int $cover
     * @param Weight $weight
     * @param bool $twoHandedOnly
     * @param Strength $maximalApplicableStrength
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\NewWeaponIsNotOfRequiredType
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\DifferentWeaponIsUnderSameName
     */
    public function addNewBow(
        RangedWeaponCode $bowCode,
        Strength $requiredStrength,
        DistanceBonus $range,
        int $offensiveness,
        int $wounds,
        PhysicalWoundTypeCode $woundTypeCode,
        int $cover,
        Weight $weight,
        bool $twoHandedOnly,
        Strength $maximalApplicableStrength
    ): bool
    {
        return $this->addCustomRangedWeapon(
            $bowCode,
            WeaponCategoryCode::getIt(WeaponCategoryCode::BOWS),
            $requiredStrength,
            $range,
            $offensiveness,
            $wounds,
            $woundTypeCode,
            $cover,
            $weight,
            $twoHandedOnly,
            [static::MAXIMAL_APPLICABLE_STRENGTH => $maximalApplicableStrength->getValue()]
        );
    }
}