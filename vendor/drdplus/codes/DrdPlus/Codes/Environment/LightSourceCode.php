<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Environment;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static LightSourceCode getIt($codeValue)
 * @method static LightSourceCode findIt($codeValue)
 */
class LightSourceCode extends AbstractCode
{
    public const EMBERS_IN_FIRE = 'embers_in_fire';
    public const CANDLE = 'candle';
    public const TRIPLE_CANDELABRA_OR_WORSE_TORCH = 'triple_candelabra_or_worse_torch';
    public const BETTER_TORCH_OR_SEVEN_CANDELABRA = 'better_torch_or_seven_candelabra';
    public const LANTERN = 'lantern';
    public const CAMP_FIRE = 'camp_fire';
    public const BALEFIRE = 'balefire';
    public const LIGHT_HOUSE = 'light_house';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::EMBERS_IN_FIRE,
            self::CANDLE,
            self::TRIPLE_CANDELABRA_OR_WORSE_TORCH,
            self::BETTER_TORCH_OR_SEVEN_CANDELABRA,
            self::LANTERN,
            self::CAMP_FIRE,
            self::BALEFIRE,
            self::LIGHT_HOUSE,
        ];
    }

}