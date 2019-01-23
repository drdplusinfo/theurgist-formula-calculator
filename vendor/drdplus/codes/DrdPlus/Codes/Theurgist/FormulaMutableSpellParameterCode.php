<?php
declare(strict_types = 1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static FormulaMutableSpellParameterCode getIt($codeValue)
 * @method static FormulaMutableSpellParameterCode findIt($codeValue)
 */
class FormulaMutableSpellParameterCode extends AbstractTheurgistCode
{
    public const RADIUS = 'radius';
    public const DURATION = 'duration';
    public const POWER = 'power';
    public const ATTACK = 'attack';
    public const SIZE_CHANGE = 'size_change';
    public const DETAIL_LEVEL = 'detail_level';
    public const BRIGHTNESS = 'brightness';
    public const SPELL_SPEED = 'spell_speed';
    public const EPICENTER_SHIFT = 'epicenter_shift';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            'radius',
            'duration',
            'power',
            'attack',
            'size_change',
            'detail_level',
            'brightness',
            'spell_speed',
            'epicenter_shift',
        ];
    }

    private static $translations = [
        'cs' => [
            self::RADIUS => 'poloměr',
            self::DURATION => 'doba trvání',
            self::POWER => 'síla',
            self::ATTACK => 'útočnost',
            self::SIZE_CHANGE => 'změna velikosti',
            self::DETAIL_LEVEL => 'detailnost',
            self::BRIGHTNESS => 'jas',
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