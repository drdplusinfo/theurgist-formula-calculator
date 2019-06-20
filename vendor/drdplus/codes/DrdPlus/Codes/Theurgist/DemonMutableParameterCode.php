<?php declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static DemonMutableParameterCode getIt($codeValue)
 * @method static DemonMutableParameterCode findIt($codeValue)
 */
class DemonMutableParameterCode extends AbstractTheurgistCode
{
    const DEMON_CAPACITY = 'demon_capacity';
    const DEMON_ENDURANCE = 'demon_endurance';
    const DEMON_ACTIVATION_DURATION = 'demon_activation_duration';
    const DEMON_QUALITY = 'demon_quality';
    const DEMON_RADIUS = 'demon_radius';
    const DEMON_AREA = 'demon_area';
    const DEMON_INVISIBILITY = 'demon_invisibility';
    const DEMON_ARMOR = 'demon_armor';
    const SPELL_SPEED = 'spell_speed';
    const DEMON_STRENGTH = 'demon_strength';
    const DEMON_AGILITY = 'demon_agility';
    const DEMON_KNACK = 'demon_knack';

    public static function getPossibleValues(): array
    {
        return [
            self::DEMON_CAPACITY,
            self::DEMON_ENDURANCE,
            self::DEMON_ACTIVATION_DURATION,
            self::DEMON_QUALITY,
            self::DEMON_RADIUS,
            self::DEMON_AREA,
            self::DEMON_INVISIBILITY,
            self::DEMON_ARMOR,
            self::SPELL_SPEED,
            self::DEMON_STRENGTH,
            self::DEMON_AGILITY,
            self::DEMON_KNACK,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                'one' => [
                    self::DEMON_CAPACITY => 'kapacita',
                    self::DEMON_ENDURANCE => 'výdrž',
                    self::DEMON_ACTIVATION_DURATION => 'doba trvání',
                    self::DEMON_QUALITY => 'kvalita',
                    self::DEMON_RADIUS => 'poloměr',
                    self::DEMON_AREA => 'oblast',
                    self::DEMON_INVISIBILITY => 'neviditelnost',
                    self::DEMON_ARMOR => 'zbroj ',
                    self::SPELL_SPEED => 'rychlost kouzla',
                    self::DEMON_STRENGTH => 'síla',
                    self::DEMON_AGILITY => 'obratnost',
                    self::DEMON_KNACK => 'zručnost',
                ],
            ],
        ];
    }

}