<?php declare(strict_types=1);

namespace DrdPlus\Codes\Environment;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static LightConditionsCode getIt($codeValue)
 * @method static LightConditionsCode findIt($codeValue)
 */
class LightConditionsCode extends AbstractCode
{
    public const DARK = 'dark';
    public const CLOUDY_STAR_NIGHT = 'cloudy_star_night';
    public const STAR_NIGHT = 'star_night';
    public const FULL_MOON_NIGHT = 'full_moon_night';
    public const SUNSET = 'sunset';
    public const VERY_CLOUDY = 'very_cloudy';
    public const CLOUDY = 'cloudy';
    public const DAYLIGHT = 'daylight';
    public const STRONG_DAYLIGHT = 'strong_daylight';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::DARK,
            self::CLOUDY_STAR_NIGHT,
            self::STAR_NIGHT,
            self::FULL_MOON_NIGHT,
            self::SUNSET,
            self::VERY_CLOUDY,
            self::CLOUDY,
            self::DAYLIGHT,
            self::STRONG_DAYLIGHT,
        ];
    }

}