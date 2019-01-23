<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\CombatActions;

/**
 * @method static MeleeCombatActionCode getIt($codeValue)
 * @method static MeleeCombatActionCode findIt($codeValue)
 */
class MeleeCombatActionCode extends CombatActionCode
{
    // See PPH page 107-109
    public const HEADLESS_ATTACK = 'headless_attack';
    public const COVER_OF_ALLY = 'cover_of_ally';
    public const FLAT_ATTACK = 'flat_attack';
    public const PRESSURE = 'pressure';
    public const RETREAT = 'retreat';

    /**
     * @return array|string[]
     */
    public static function getMeleeOnlyCombatActionValues(): array
    {
        return [
            self::HEADLESS_ATTACK,
            self::COVER_OF_ALLY,
            self::FLAT_ATTACK,
            self::PRESSURE,
            self::RETREAT,
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return \array_merge(
            parent::getPossibleValues(),
            self::getMeleeOnlyCombatActionValues()
        );
    }

    /**
     * @return bool
     */
    public function isForRanged(): bool
    {
        // only actions inherited from generic combat actions can be used for ranged attack
        return !\in_array($this->getValue(), self::getMeleeOnlyCombatActionValues(), true);
    }
}