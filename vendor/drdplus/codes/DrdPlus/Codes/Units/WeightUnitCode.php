<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Units;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static WeightUnitCode getIt($codeValue)
 * @method static WeightUnitCode findIt($codeValue)
 */
class WeightUnitCode extends TranslatableCode
{
    public const KG = 'kg';

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [self::KG => [self::$ONE => 'kilogram', self::$FEW => 'kilogramy', self::$MANY => 'kilogramů']],
            'en' => [self::KG => [self::$ONE => 'kg', self::$FEW => 'kgs', self::$MANY => 'kgs']],
        ];
    }

    public static function getPossibleValues(): array
    {
        return [self::KG];
    }

}