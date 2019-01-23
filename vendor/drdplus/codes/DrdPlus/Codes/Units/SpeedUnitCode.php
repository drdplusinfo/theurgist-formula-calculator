<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Units;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static SpeedUnitCode getIt($codeValue)
 * @method static SpeedUnitCode findIt($codeValue)
 */
class SpeedUnitCode extends TranslatableCode
{
    public const METER_PER_ROUND = 'meter_per_round';
    public const KILOMETER_PER_HOUR = 'kilometer_per_hour';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::METER_PER_ROUND,
            self::KILOMETER_PER_HOUR,
        ];
    }

    /**
     * @return array|string[]
     */
    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::METER_PER_ROUND => [self::$ONE => 'meter per round', self::$FEW => 'meters per round', self::$MANY => 'meters per round'],
                self::KILOMETER_PER_HOUR => [self::$ONE => 'kilometer per hour', self::$FEW => 'kilometers per hour', self::$MANY => 'kilometers per hour'],
            ],
            'cs' => [
                self::METER_PER_ROUND => [self::$ONE => 'metr za kolo', self::$FEW => 'metry za kolo', self::$MANY => 'metrů za kolo'],
                self::KILOMETER_PER_HOUR => [self::$ONE => 'kilometr za hodinu', self::$FEW => 'kilometry za hodinu', self::$MANY => 'kilometrů za hodinu'],
            ],
        ];
    }
}