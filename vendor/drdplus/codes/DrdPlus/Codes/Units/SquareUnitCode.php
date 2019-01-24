<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Units;

use DrdPlus\Codes\Partials\FileBasedTranslatableCode;

/**
 * @method static SquareUnitCode getIt($codeValue)
 * @method static SquareUnitCode findIt($codeValue)
 */
class SquareUnitCode extends FileBasedTranslatableCode
{
    public const SQUARE_DECIMETER = 'square_decimeter';
    public const SQUARE_METER = 'square_meter';
    public const SQUARE_KILOMETER = 'square_kilometer';

    public static function getPossibleValues(): array
    {
        return [
            self::SQUARE_DECIMETER,
            self::SQUARE_METER,
            self::SQUARE_KILOMETER,
        ];
    }

    protected function getTranslationsFileName(): string
    {
        return __DIR__ . '/translations/square_unit_code.csv';
    }
}