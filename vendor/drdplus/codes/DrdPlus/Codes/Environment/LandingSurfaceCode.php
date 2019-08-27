<?php declare(strict_types=1);

namespace DrdPlus\Codes\Environment;

use DrdPlus\Codes\Partials\FileBasedTranslatableCode;

/**
 * @method static LandingSurfaceCode getIt($codeValue)
 * @method static LandingSurfaceCode findIt($codeValue)
 */
class LandingSurfaceCode extends FileBasedTranslatableCode
{
    public const DEEP_POWDER = 'deep_powder';
    public const WATER = 'water';
    public const HEAVY_WET_SNOW = 'heavy_wet_snow';
    public const FRESHLY_PLOWED_FIELD_OR_PILE_OF_LEAVES = 'freshly_plowed_field_or_pile_of_leaves';
    public const MEADOW = 'meadow';
    public const SOLID_ICE_OR_ICY_SNOW_OR_TAMPED_EARTH = 'solid_ice_or_icy_snow_or_tamped_earth';
    public const FLAT_STONE_TILES = 'flat_stone_tiles';
    public const BUMPY_ROCK = 'bumpy_rock';
    public const SHARP_ROCKS_OR_POINTED_PALES = 'sharp_rocks_or_pointed_pales';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::DEEP_POWDER,
            self::WATER,
            self::HEAVY_WET_SNOW,
            self::FRESHLY_PLOWED_FIELD_OR_PILE_OF_LEAVES,
            self::MEADOW,
            self::SOLID_ICE_OR_ICY_SNOW_OR_TAMPED_EARTH,
            self::FLAT_STONE_TILES,
            self::BUMPY_ROCK,
            self::SHARP_ROCKS_OR_POINTED_PALES,
        ];
    }

    protected function getTranslationsFileName(): string
    {
        return __DIR__ . '/translations/landing_surfaces.csv';
    }

}