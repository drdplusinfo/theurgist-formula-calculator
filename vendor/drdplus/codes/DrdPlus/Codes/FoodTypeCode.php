<?php
declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static FoodTypeCode getIt($codeValue)
 * @method static FoodTypeCode findIt($codeValue)
 */
class FoodTypeCode extends AbstractCode
{
    public const CROP_COLLECTION = 'crop_collection';
    public const INSECTS_OR_WORMS = 'insects_or_worms';
    public const SLUGS = 'slugs';
    public const REPTILES = 'reptiles';
    public const RODENTS_OR_BIRDS_EGGS = 'rodents_or_birds_eggs';
    public const AVERAGE_MEAT = 'average_meat';
    public const QUALITY_MEAT = 'quality_meat';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::CROP_COLLECTION,
            self::INSECTS_OR_WORMS,
            self::SLUGS,
            self::REPTILES,
            self::RODENTS_OR_BIRDS_EGGS,
            self::AVERAGE_MEAT,
            self::QUALITY_MEAT,
        ];
    }

}