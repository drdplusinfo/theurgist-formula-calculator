<?php
declare(strict_types = 1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static FormulaMutableSpellParameterCode getIt($codeValue)
 * @method static FormulaMutableSpellParameterCode findIt($codeValue)
 */
class FormulaMutableSpellParameterCode extends AbstractTheurgistCode
{
    public const SPELL_RADIUS = 'spell_radius';
    public const SPELL_DURATION = 'spell_duration';
    public const SPELL_POWER = 'spell_power';
    public const SPELL_ATTACK = 'spell_attack';
    public const SIZE_CHANGE = 'size_change';
    public const DETAIL_LEVEL = 'detail_level';
    public const SPELL_BRIGHTNESS = 'spell_brightness';
    public const SPELL_SPEED = 'spell_speed';
    public const EPICENTER_SHIFT = 'epicenter_shift';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::SPELL_RADIUS,
            self::SPELL_DURATION,
            self::SPELL_POWER,
            self::SPELL_ATTACK,
            self::SIZE_CHANGE,
            self::DETAIL_LEVEL,
            self::SPELL_BRIGHTNESS,
            self::SPELL_SPEED,
            self::EPICENTER_SHIFT,
        ];
    }

    private static $translations = [
        'cs' => [
            self::SPELL_RADIUS => 'poloměr',
            self::SPELL_DURATION => 'doba trvání',
            self::SPELL_POWER => 'síla',
            self::SPELL_ATTACK => 'útočnost',
            self::SIZE_CHANGE => 'změna velikosti',
            self::DETAIL_LEVEL => 'detailnost',
            self::SPELL_BRIGHTNESS => 'jas',
            self::SPELL_SPEED => 'rychlost',
            self::EPICENTER_SHIFT => 'posun',
        ],
    ];

    /**
     * @param string $languageCode
     * @return array|string[]
     */
    protected function getTranslations(string $languageCode): array
    {
        return self::$translations[$languageCode] ?? [];
    }

}