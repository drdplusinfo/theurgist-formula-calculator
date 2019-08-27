<?php declare(strict_types=1);

namespace DrdPlus\Codes\Units;

use DrdPlus\Codes\Partials\FileBasedTranslatableCode;

/**
 * @method static VolumeUnitCode getIt($codeValue)
 * @method static VolumeUnitCode findIt($codeValue)
 */
class VolumeUnitCode extends FileBasedTranslatableCode
{
    public const LITER = 'liter';
    public const CUBIC_METER = 'cubic_meter';
    public const CUBIC_KILOMETER = 'cubic_kilometer';

    public static function getPossibleValues(): array
    {
        return [
            self::LITER,
            self::CUBIC_METER,
            self::CUBIC_KILOMETER,
        ];
    }

    protected function getTranslationsFileName(): string
    {
        return __DIR__ . '/translations/volume_unit_code.csv';
    }
}