<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Environment;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static TerrainCode getIt($codeValue)
 * @method static TerrainCode findIt($codeValue)
 */
class TerrainCode extends AbstractCode
{
    public const ROAD = 'road';
    public const MEADOW = 'meadow';
    public const FOREST = 'forest';
    public const JUNGLE = 'jungle';
    public const SWAMP = 'swamp';
    public const MOUNTAINS = 'mountains';
    public const DESERT = 'desert';
    public const ICY_PLAINS = 'icy_plains';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::ROAD,
            self::MEADOW,
            self::FOREST,
            self::JUNGLE,
            self::SWAMP,
            self::MOUNTAINS,
            self::DESERT,
            self::ICY_PLAINS,
        ];
    }
}