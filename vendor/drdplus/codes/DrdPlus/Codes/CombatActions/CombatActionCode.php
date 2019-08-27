<?php declare(strict_types=1);

namespace DrdPlus\Codes\CombatActions;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static CombatActionCode getIt($codeValue)
 * @method static CombatActionCode findIt($codeValue)
 */
class CombatActionCode extends AbstractCode
{
    // See PPH page 107-109
    public const MOVE = 'move';
    public const RUN = 'run';
    public const SWAP_WEAPONS = 'swap_weapons';
    public const CONCENTRATION_ON_DEFENSE = 'concentration_on_defense'; // this is moved to generic combat action despite its categorization as melee in PPH
    public const PUT_OUT_EASILY_ACCESSIBLE_ITEM = 'put_out_easily_accessible_item'; // from belt or ground etc.
    public const PUT_OUT_HARDLY_ACCESSIBLE_ITEM = 'put_out_hardly_accessible_item'; // from backpack or just using both hands
    public const LAYING = 'laying';
    public const SITTING_OR_ON_KNEELS = 'sitting_or_on_kneels';
    public const GETTING_UP = 'getting_up';
    public const PUTTING_ON_ARMOR = 'putting_on_armor';
    public const PUTTING_ON_ARMOR_WITH_HELP = 'putting_on_armor_with_help';
    public const HELPING_TO_PUT_ON_ARMOR = 'helping_to_put_on_armor';
    public const ATTACKED_FROM_BEHIND = 'attacked_from_behind';
    public const BLINDFOLD_FIGHT = 'blindfold_fight';
    public const FIGHT_IN_REDUCED_VISIBILITY = 'fight_in_reduced_visibility';
    public const ATTACK_ON_DISABLED_OPPONENT = 'attack_on_disabled_opponent'; // this is moved to generic combat action despite its categorization as melee in PPH
    public const HANDOVER_ITEM = 'handover_item';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::MOVE,
            self::RUN,
            self::SWAP_WEAPONS,
            self::CONCENTRATION_ON_DEFENSE,
            self::PUT_OUT_EASILY_ACCESSIBLE_ITEM,
            self::PUT_OUT_HARDLY_ACCESSIBLE_ITEM,
            self::LAYING,
            self::SITTING_OR_ON_KNEELS,
            self::GETTING_UP,
            self::PUTTING_ON_ARMOR,
            self::PUTTING_ON_ARMOR_WITH_HELP,
            self::HELPING_TO_PUT_ON_ARMOR,
            self::ATTACKED_FROM_BEHIND,
            self::BLINDFOLD_FIGHT,
            self::FIGHT_IN_REDUCED_VISIBILITY,
            self::ATTACK_ON_DISABLED_OPPONENT,
            self::HANDOVER_ITEM,
        ];
    }

    /**
     * @return bool
     */
    public function isForMelee(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isForRanged(): bool
    {
        return true;
    }
}