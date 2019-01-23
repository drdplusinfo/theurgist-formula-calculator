<?php
declare(strict_types = 1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static FormulaCode getIt($codeValue)
 * @method static FormulaCode findIt($codeValue)
 */
class FormulaCode extends AbstractTheurgistCode
{
    public const BARRIER = 'barrier';
    public const SMOKE = 'smoke';
    public const ILLUSION = 'illusion';
    public const METAMORPHOSIS = 'metamorphosis';
    public const FIRE = 'fire';
    public const PORTAL = 'portal';
    public const LIGHT = 'light';
    public const FLOW_OF_TIME = 'flow_of_time';
    public CONST TSUNAMI_FROM_CLAY_AND_STONES = 'tsunami_from_clay_and_stones';
    public const HIT = 'hit';
    public const GREAT_MASSACRE = 'great_massacre';
    public const DISCHARGE = 'discharge';
    public const LOCK = 'lock';

    public static function getPossibleValues(): array
    {
        return [
            self::BARRIER,
            self::SMOKE,
            self::ILLUSION,
            self::METAMORPHOSIS,
            self::FIRE,
            self::PORTAL,
            self::LIGHT,
            self::FLOW_OF_TIME,
            self::TSUNAMI_FROM_CLAY_AND_STONES,
            self::HIT,
            self::GREAT_MASSACRE,
            self::DISCHARGE,
            self::LOCK,
        ];
    }

    /**
     * @param string $languageCode
     * @return array|string[]
     */
    protected function getTranslations(string $languageCode): array
    {
        return self::$translations[$languageCode] ?? [];
    }

    private static $translations = [
        'cs' => [
            self::BARRIER => 'bariéra',
            self::SMOKE => 'dým',
            self::ILLUSION => 'iluze',
            self::METAMORPHOSIS => 'metamorfóza',
            self::FIRE => 'oheň',
            self::PORTAL => 'portál',
            self::LIGHT => 'světlo',
            self::FLOW_OF_TIME => 'tok času',
            self::TSUNAMI_FROM_CLAY_AND_STONES => 'tsunami z hlíny a kamení',
            self::HIT => 'úder',
            self::GREAT_MASSACRE => 'velký mord',
            self::DISCHARGE => 'výboj',
            self::LOCK => 'zamčení',
        ],
    ];

}