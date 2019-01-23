<?php
declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static ActivityIntensityCode getIt($codeValue)
 * @method static ActivityIntensityCode findIt($codeValue)
 */
class ActivityIntensityCode extends AbstractCode
{
    public const AUTOMATIC_ACTIVITY = 'automatic_activity';
    public const ACTIVITY_WITH_MODERATE_CONCENTRATION = 'activity_with_moderate_concentration';
    public const ACTIVITY_WITH_FULL_CONCENTRATION = 'activity_with_full_concentration';
    public const TRANS = 'trans';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::AUTOMATIC_ACTIVITY,
            self::ACTIVITY_WITH_MODERATE_CONCENTRATION,
            self::ACTIVITY_WITH_FULL_CONCENTRATION,
            self::TRANS,
        ];
    }

}