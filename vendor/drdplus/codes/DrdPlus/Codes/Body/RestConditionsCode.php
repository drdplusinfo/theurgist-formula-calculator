<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Body;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static RestConditionsCode getIt($codeValue)
 * @method static RestConditionsCode findIt($codeValue)
 */
class RestConditionsCode extends AbstractCode
{
    public const HALF_TIME_OF_REST_OR_SLEEP = 'half_time_of_rest_or_sleep';
    public const QUARTER_TIME_OF_REST_OR_SLEEP = 'quarter_time_of_rest_or_sleep';
    public const FOUL_CONDITIONS = ConditionsAffectingHealingCode::FOUL_CONDITIONS;
    public const BAD_CONDITIONS = ConditionsAffectingHealingCode::BAD_CONDITIONS;
    public const IMPAIRED_CONDITIONS = ConditionsAffectingHealingCode::IMPAIRED_CONDITIONS;
    public const GOOD_CONDITIONS = ConditionsAffectingHealingCode::GOOD_CONDITIONS;

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::HALF_TIME_OF_REST_OR_SLEEP,
            self::QUARTER_TIME_OF_REST_OR_SLEEP,
            self::FOUL_CONDITIONS,
            self::BAD_CONDITIONS,
            self::IMPAIRED_CONDITIONS,
            self::GOOD_CONDITIONS,
        ];
    }
}