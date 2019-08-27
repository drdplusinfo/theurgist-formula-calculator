<?php declare(strict_types=1);

namespace DrdPlus\Codes\Armaments;

use DrdPlus\Codes\Partials\FileBasedTranslatableCode;

/**
 * @method static WeaponCategoryCode getIt($codeValue)
 * @method static WeaponCategoryCode findIt($codeValue)
 */
class WeaponCategoryCode extends FileBasedTranslatableCode
{
    // MELEE WEAPON CATEGORIES

    public const AXES = 'axes';
    public const KNIVES_AND_DAGGERS = 'knives_and_daggers';
    public const MACES_AND_CLUBS = 'maces_and_clubs';
    public const MORNINGSTARS_AND_MORGENSTERNS = 'morningstars_and_morgensterns';
    public const SABERS_AND_BOWIE_KNIVES = 'sabers_and_bowie_knives';
    public const STAFFS_AND_SPEARS = 'staffs_and_spears';
    public const SWORDS = 'swords';
    public const VOULGES_AND_TRIDENTS = 'voulges_and_tridents';
    public const UNARMED = 'unarmed';

    /**
     * @return array|string[]
     */
    public static function getMeleeWeaponCategoryValues(): array
    {
        return [
            self::AXES,
            self::KNIVES_AND_DAGGERS,
            self::MACES_AND_CLUBS,
            self::MORNINGSTARS_AND_MORGENSTERNS,
            self::SABERS_AND_BOWIE_KNIVES,
            self::STAFFS_AND_SPEARS,
            self::SWORDS,
            self::VOULGES_AND_TRIDENTS,
            self::UNARMED,
        ];
    }

    public function isMeleeWeaponCategory(): bool
    {
        return \in_array($this->getValue(), static::getMeleeWeaponCategoryValues(), true);
    }

    // RANGE WEAPON CATEGORIES

    // shooting weapons
    public const BOWS = 'bows';
    public const CROSSBOWS = 'crossbows';
    // throwing weapons
    public const THROWING_WEAPONS = 'throwing_weapons';

    /**
     * @return array|string[]
     */
    public static function getRangedWeaponCategoryValues(): array
    {
        return [
            self::BOWS,
            self::CROSSBOWS,
            self::THROWING_WEAPONS,
        ];
    }

    public function isRangedWeaponCategory(): bool
    {
        return \in_array($this->getValue(), static::getRangedWeaponCategoryValues(), true);
    }

    // projectiles
    public const ARROWS = 'arrows';
    public const DARTS = 'darts';
    public const SLING_STONES = 'sling_stones';

    /**
     * @return array|string[]
     */
    public static function getProjectileCategoryValues(): array
    {
        return [
            self::ARROWS,
            self::DARTS,
            self::SLING_STONES,
        ];
    }

    public function isProjectileCategory(): bool
    {
        return \in_array($this->getValue(), static::getProjectileCategoryValues(), true);
    }

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return \array_merge(
            self::getMeleeWeaponCategoryValues(),
            self::getRangedWeaponCategoryValues(),
            self::getProjectileCategoryValues()
        );
    }

    protected function getTranslationsFileName(): string
    {
        return __DIR__ . '/translations/weapon_category_code.csv';
    }

}