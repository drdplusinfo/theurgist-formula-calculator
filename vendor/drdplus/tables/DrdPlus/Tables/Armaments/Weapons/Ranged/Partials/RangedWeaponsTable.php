<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Weapons\Ranged\Partials;

use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon;
use DrdPlus\Tables\Armaments\Weapons\WeaponsTable;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

abstract class RangedWeaponsTable extends WeaponsTable
{
    public const RANGE = 'range';
    public const WEAPON = 'weapon';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::WEAPON];
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REQUIRED_STRENGTH => self::INTEGER,
            self::OFFENSIVENESS => self::INTEGER,
            self::WOUNDS => self::INTEGER,
            self::WOUNDS_TYPE => self::STRING,
            self::RANGE => self::INTEGER,
            self::COVER => self::INTEGER,
            self::WEIGHT => self::FLOAT,
            self::TWO_HANDED_ONLY => self::BOOLEAN,
        ];
    }

    /**
     * @param RangedWeaponCode $rangedWeaponCode
     * @param WeaponCategoryCode $rangedWeaponCategoryCode
     * @param Strength $requiredStrength
     * @param DistanceBonus $range
     * @param int $offensiveness
     * @param int $wounds
     * @param PhysicalWoundTypeCode $woundTypeCode
     * @param int $cover
     * @param Weight $weight
     * @param bool $twoHandedOnly
     * @param array $additionalParameters
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\NewWeaponIsNotOfRequiredType
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\DifferentWeaponIsUnderSameName
     */
    public function addCustomRangedWeapon(
        RangedWeaponCode $rangedWeaponCode,
        WeaponCategoryCode $rangedWeaponCategoryCode,
        Strength $requiredStrength,
        DistanceBonus $range,
        int $offensiveness,
        int $wounds,
        PhysicalWoundTypeCode $woundTypeCode,
        int $cover,
        Weight $weight,
        bool $twoHandedOnly,
        array $additionalParameters
    ): bool
    {
        return $this->addNewCustomWeapon(
            $rangedWeaponCode,
            $rangedWeaponCategoryCode,
            \array_merge(
                $additionalParameters,
                [
                    self::REQUIRED_STRENGTH => $requiredStrength->getValue(),
                    self::OFFENSIVENESS => $offensiveness,
                    self::RANGE => $range->getValue(), // distance bonus in fact
                    self::WOUNDS => $wounds,
                    self::WOUNDS_TYPE => $woundTypeCode->getValue(),
                    self::COVER => $cover,
                    self::WEIGHT => $weight->getKilograms(),
                    self::TWO_HANDED_ONLY => $twoHandedOnly,
                ]
            )
        );
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getOffensivenessOf($weaponlikeCode): int
    {
        return $this->getValueOf($weaponlikeCode, self::OFFENSIVENESS);
    }

    /**
     * @param string|StringInterface|RangedWeaponCode $rangedWeaponCode
     * @param string $valueName
     * @return float|int|string|bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    protected function getValueOf($rangedWeaponCode, string $valueName)
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue([$rangedWeaponCode], $valueName);
        } catch (RequiredRowNotFound $exception) {
            throw new UnknownRangedWeapon(
                'Unknown ranged armament code ' . ValueDescriber::describe($rangedWeaponCode)
            );
        }
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getWoundsOf($weaponlikeCode): int
    {
        return $this->getValueOf($weaponlikeCode, self::WOUNDS);
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return string
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getWoundsTypeOf($weaponlikeCode): string
    {
        return $this->getValueOf($weaponlikeCode, self::WOUNDS_TYPE);
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return int|false
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getRequiredStrengthOf($weaponlikeCode)
    {
        return $this->getValueOf($weaponlikeCode, self::REQUIRED_STRENGTH);
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getRangeOf($weaponlikeCode): int
    {
        return $this->getValueOf($weaponlikeCode, self::RANGE);
    }

    /**
     * Every ranged weapon is considered as with cover of 2 (projectiles 0), see PPH page 94, right column
     *
     * @param string|StringInterface $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getCoverOf($weaponlikeCode): int
    {
        return $this->getValueOf($weaponlikeCode, self::COVER);
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return float
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getWeightOf($weaponlikeCode): float
    {
        return $this->getValueOf($weaponlikeCode, self::WEIGHT);
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getTwoHandedOnlyOf($weaponlikeCode): bool
    {
        return $this->getValueOf($weaponlikeCode, self::TWO_HANDED_ONLY);
    }
}