<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Environment;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static ItemStealthinessCode getIt($codeValue)
 * @method static ItemStealthinessCode findIt($codeValue)
 */
class ItemStealthinessCode extends AbstractCode
{
    public const CLEARLY_VISIBLE_ITEM = 'clearly_visible_item';
    public const ITEM_IN_ALIGNMENT = 'item_in_alignment';
    public const COVERED_ITEM = 'covered_item';
    public const HIDDEN_ITEM = 'hidden_item';
    public const WELL_HIDDEN_ITEM = 'well_hidden_item';
    public const VERY_WELL_HIDDEN_ITEM = 'very_well_hidden_item';
    public const PERFECTLY_HIDDEN_ITEM = 'perfectly_hidden_item';
    public const TOTALLY_HIDDEN_ITEM = 'totally_hidden_item';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::CLEARLY_VISIBLE_ITEM,
            self::ITEM_IN_ALIGNMENT,
            self::COVERED_ITEM,
            self::HIDDEN_ITEM,
            self::WELL_HIDDEN_ITEM,
            self::VERY_WELL_HIDDEN_ITEM,
            self::PERFECTLY_HIDDEN_ITEM,
            self::TOTALLY_HIDDEN_ITEM,
        ];
    }

}