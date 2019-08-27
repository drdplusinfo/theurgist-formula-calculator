<?php declare(strict_types=1);

namespace DrdPlus\Codes\History;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static FateCode getIt($codeValue)
 * @method static FateCode findIt($codeValue)
 */
class FateCode extends AbstractCode
{
    public const EXCEPTIONAL_PROPERTIES = 'exceptional_properties';
    public const COMBINATION_OF_PROPERTIES_AND_BACKGROUND = 'combination_of_properties_and_background';
    public const GOOD_BACKGROUND = 'good_background';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::EXCEPTIONAL_PROPERTIES,
            self::COMBINATION_OF_PROPERTIES_AND_BACKGROUND,
            self::GOOD_BACKGROUND,
        ];
    }

}