<?php
declare(strict_types=1);

namespace DrdPlus\Codes\CombatActions;

/**
 * @method static RangedCombatActionCode getIt($codeValue)
 * @method static RangedCombatActionCode findIt($codeValue)
 */
class RangedCombatActionCode extends CombatActionCode
{
    // See PPH page 108
    public const AIMED_SHOT = 'aimed_shot';

    public static function getRangedOnlyCombatActionValues(): array
    {
        return [self::AIMED_SHOT];
    }

    /**
     * @return array|\string[]
     */
    public static function getPossibleValues(): array
    {
        $rangedCombatActions = parent::getPossibleValues();
        $rangedCombatActions[] = self::AIMED_SHOT;

        return $rangedCombatActions;
    }

    /**
     * @return bool
     */
    public function isForMelee(): bool
    {
        // only actions inherited from generic combat actions can be used for melee attack
        return $this->getValue() !== self::AIMED_SHOT;
    }
}