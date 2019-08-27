<?php declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static CombatCharacteristicCode getIt($codeValue)
 * @method static CombatCharacteristicCode findIt($codeValue)
 */
class CombatCharacteristicCode extends AbstractCode
{
    public const ATTACK = 'attack';
    public const DEFENSE = 'defense';
    public const SHOOTING = 'shooting';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::ATTACK,
            self::DEFENSE,
            self::SHOOTING,
        ];
    }

}