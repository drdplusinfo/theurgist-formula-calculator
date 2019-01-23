<?php
declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static SearchingItemTypeCode getIt($codeValue)
 * @method static SearchingItemTypeCode findIt($codeValue)
 */
class SearchingItemTypeCode extends AbstractCode
{
    public const SEARCHING_SAME_TYPE_ITEM = 'searching_same_type_item';
    public const JUST_SEARCHING = 'just_searching';
    public const SEARCHING_DIFFERENT_TYPE_ITEM = 'searching_different_type_item';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::SEARCHING_SAME_TYPE_ITEM,
            self::JUST_SEARCHING,
            self::SEARCHING_DIFFERENT_TYPE_ITEM,
        ];
    }

}