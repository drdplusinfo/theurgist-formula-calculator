<?php declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static ModifierMutableParameterCode getIt($codeValue)
 * @method static ModifierMutableParameterCode findIt($codeValue)
 */
class ModifierMutableParameterCode extends AbstractTheurgistCode
{
    public const SPELL_RADIUS = FormulaMutableParameterCode::SPELL_RADIUS;
    public const EPICENTER_SHIFT = FormulaMutableParameterCode::EPICENTER_SHIFT;
    public const SPELL_POWER = FormulaMutableParameterCode::SPELL_POWER;
    public const NOISE = 'noise';
    public const SPELL_ATTACK = FormulaMutableParameterCode::SPELL_ATTACK;
    public const GRAFTS = 'grafts';
    public const SPELL_SPEED = FormulaMutableParameterCode::SPELL_SPEED;
    public const NUMBER_OF_WAYPOINTS = 'number_of_waypoints';
    public const INVISIBILITY = 'invisibility';
    public const QUALITY = 'quality';
    public const NUMBER_OF_CONDITIONS = 'number_of_conditions';
    public const RESISTANCE = 'resistance';
    public const NUMBER_OF_SITUATIONS = 'number_of_situations';
    public const THRESHOLD = 'threshold';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::SPELL_RADIUS,
            self::EPICENTER_SHIFT,
            self::SPELL_POWER,
            self::NOISE,
            self::SPELL_ATTACK,
            self::GRAFTS,
            self::SPELL_SPEED,
            self::NUMBER_OF_WAYPOINTS,
            self::INVISIBILITY,
            self::QUALITY,
            self::NUMBER_OF_CONDITIONS,
            self::RESISTANCE,
            self::NUMBER_OF_SITUATIONS,
            self::THRESHOLD,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                'one' => [
                    self::SPELL_RADIUS => 'poloměr',
                    self::EPICENTER_SHIFT => 'posun',
                    self::SPELL_POWER => 'síla',
                    self::NOISE => 'síla zvuku',
                    self::SPELL_ATTACK => 'útočnost',
                    self::GRAFTS => 'štěpy',
                    self::SPELL_SPEED => 'rychlost',
                    self::NUMBER_OF_WAYPOINTS => 'počet průchodů',
                    self::INVISIBILITY => 'neviditelnost',
                    self::QUALITY => 'kvalita',
                    self::NUMBER_OF_CONDITIONS => 'počet podmínek',
                    self::RESISTANCE => 'odolnost',
                    self::NUMBER_OF_SITUATIONS => 'počet situací',
                    self::THRESHOLD => 'práh citlivosti',
                ],
            ],
        ];
    }

}