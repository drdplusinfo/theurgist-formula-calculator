<?php declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static ElementCode getIt($codeValue)
 * @method static ElementCode findIt($codeValue)
 */
class ElementCode extends AbstractCode
{
    public const FIRE = 'fire';
    public const WATER = 'water';
    public const EARTH = 'earth';
    public const AIR = 'air';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::FIRE,
            self::WATER,
            self::EARTH,
            self::AIR,
        ];
    }
}