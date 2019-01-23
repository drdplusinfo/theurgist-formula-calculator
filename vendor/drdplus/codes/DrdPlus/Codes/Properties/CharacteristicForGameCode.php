<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Properties;

use DrdPlus\Codes\CombatCharacteristicCode;
use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static CharacteristicForGameCode getIt($codeValue)
 * @method static CharacteristicForGameCode findIt($codeValue)
 */
class CharacteristicForGameCode extends AbstractCode
{
    public const ATTACK = CombatCharacteristicCode::ATTACK;
    public const DEFENSE = CombatCharacteristicCode::DEFENSE;
    public const SHOOTING = CombatCharacteristicCode::SHOOTING;

    public const ATTACK_NUMBER = 'attack_number';
    public const DEFENSE_NUMBER = 'defense_number';
    public const ENCOUNTER_RANGE = 'encounter_range';
    public const FIGHT = 'fight';
    public const FIGHT_NUMBER = 'fight_number';
    public const LOADING_IN_ROUNDS = 'loading_in_rounds';
    public const MAXIMAL_RANGE = 'maximal_range';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::ATTACK,
            self::DEFENSE,
            self::SHOOTING,
            self::ATTACK_NUMBER,
            self::DEFENSE_NUMBER,
            self::ENCOUNTER_RANGE,
            self::FIGHT,
            self::FIGHT_NUMBER,
            self::LOADING_IN_ROUNDS,
            self::MAXIMAL_RANGE,
        ];
    }

}