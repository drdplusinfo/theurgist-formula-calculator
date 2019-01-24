<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Environment;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static MaterialCode getIt($codeValue)
 * @method static MaterialCode findIt($codeValue)
 */
class MaterialCode extends TranslatableCode
{
    public const CLOTH_OR_PAPER_OR_ROPE = 'cloth_or_paper_or_rope';
    public const WOOD = 'wood';
    public const BAKED_CAY = 'baked_cay';
    public const STONE = 'stone';
    public const BRONZE = 'bronze';
    public const IRON_OR_STEEL = 'iron_or_steel';

    /**
     * @return array
     */
    public static function getPossibleValues(): array
    {
        return [
            self::CLOTH_OR_PAPER_OR_ROPE,
            self::WOOD,
            self::BAKED_CAY,
            self::STONE,
            self::BRONZE,
            self::IRON_OR_STEEL,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::CLOTH_OR_PAPER_OR_ROPE => [self::$ONE => 'cloth or paper or rope'],
                self::WOOD => [self::$ONE => 'wood'],
                self::BAKED_CAY => [self::$ONE => 'baked cay'],
                self::STONE => [self::$ONE => 'stone'],
                self::BRONZE => [self::$ONE => 'bronze'],
                self::IRON_OR_STEEL => [self::$ONE => 'iron or steel'],
            ],
            'cs' => [
                self::CLOTH_OR_PAPER_OR_ROPE => [self::$ONE => 'oblečení, papír, lano...'],
                self::WOOD => [self::$ONE => 'dřevo'],
                self::BAKED_CAY => [self::$ONE => 'pálená hlína'],
                self::STONE => [self::$ONE => 'kámen'],
                self::BRONZE => [self::$ONE => 'bronz'],
                self::IRON_OR_STEEL => [self::$ONE => 'železo, ocel...'],
            ]
        ];
    }

}