<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Units;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static DistanceUnitCode getIt($codeValue)
 * @method static DistanceUnitCode findIt($codeValue)
 */
class DistanceUnitCode extends TranslatableCode
{
    public const DECIMETER = 'decimeter';
    public const METER = 'meter';
    public const KILOMETER = 'kilometer';
    public const LIGHT_YEAR = 'light_year';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::DECIMETER,
            self::METER,
            self::KILOMETER,
            self::LIGHT_YEAR,
        ];
    }

    /**
     * @return array|string[]
     */
    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::DECIMETER => [self::$ONE => 'decimeter', 'one_decimal' => 'decimeter', self::$FEW => 'decimeters', self::$MANY => 'decimeters'],
                self::METER => [self::$ONE => 'meter', 'one_decimal' => 'meter', self::$FEW => 'meters', self::$MANY => 'meters'],
                self::KILOMETER => [self::$ONE => 'kilometer', self::$FEW => 'kilometers', self::$MANY => 'kilometers'],
                self::LIGHT_YEAR => [self::$ONE => 'light year', self::$FEW => 'light years', self::$MANY => 'light years'],
            ],
            'cs' => [
                self::DECIMETER => [self::$ONE => 'decimetr', self::$FEW => 'decimetry', self::$FEW_DECIMAL => 'decimetru', self::$MANY => 'decimetrů'],
                self::METER => [self::$ONE => 'metr', self::$FEW => 'metry', self::$FEW_DECIMAL => 'metru', self::$MANY => 'metrů'],
                self::KILOMETER => [self::$ONE => 'kilometr', self::$FEW => 'kilometry', self::$FEW_DECIMAL => 'kilometru', self::$MANY => 'kilometrů'],
                self::LIGHT_YEAR => [self::$ONE => 'světelný rok', self::$FEW => 'světelné roky', self::$FEW_DECIMAL => 'světelného roku', self::$MANY => 'světelných let'],
            ],
        ];
    }
}