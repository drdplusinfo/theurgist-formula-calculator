<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Combat\Actions;

use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\CombatActions\CombatActionCode;
use DrdPlus\Codes\CombatActions\MeleeCombatActionCode;
use DrdPlus\Codes\CombatActions\RangedCombatActionCode;
use DrdPlus\Tables\Partials\AbstractFileTable;

/**
 * See PPH page 102, @link https://pph.drdplus.info/#bojove_akce
 * and PPH page 107, @link https://pph.drdplus.info/#dalsi_bojove_akce
 */
class CombatActionsWithWeaponTypeCompatibilityTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/combat_actions_with_weapon_type_compatibility.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            CombatActionCode::MOVE => self::BOOLEAN,
            CombatActionCode::RUN => self::BOOLEAN,
            CombatActionCode::SWAP_WEAPONS => self::BOOLEAN,
            CombatActionCode::CONCENTRATION_ON_DEFENSE => self::BOOLEAN,
            CombatActionCode::PUT_OUT_EASILY_ACCESSIBLE_ITEM => self::BOOLEAN,
            CombatActionCode::PUT_OUT_HARDLY_ACCESSIBLE_ITEM => self::BOOLEAN,
            CombatActionCode::LAYING => self::BOOLEAN,
            CombatActionCode::SITTING_OR_ON_KNEELS => self::BOOLEAN,
            CombatActionCode::GETTING_UP => self::BOOLEAN,
            CombatActionCode::PUTTING_ON_ARMOR => self::BOOLEAN,
            CombatActionCode::PUTTING_ON_ARMOR_WITH_HELP => self::BOOLEAN,
            CombatActionCode::HELPING_TO_PUT_ON_ARMOR => self::BOOLEAN,
            CombatActionCode::ATTACKED_FROM_BEHIND => self::BOOLEAN,
            CombatActionCode::BLINDFOLD_FIGHT => self::BOOLEAN,
            CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY => self::BOOLEAN,
            CombatActionCode::ATTACK_ON_DISABLED_OPPONENT => self::BOOLEAN,
            CombatActionCode::HANDOVER_ITEM => self::BOOLEAN,
            MeleeCombatActionCode::HEADLESS_ATTACK => self::BOOLEAN,
            MeleeCombatActionCode::COVER_OF_ALLY => self::BOOLEAN,
            MeleeCombatActionCode::FLAT_ATTACK => self::BOOLEAN,
            MeleeCombatActionCode::PRESSURE => self::BOOLEAN,
            MeleeCombatActionCode::RETREAT => self::BOOLEAN,
            RangedCombatActionCode::AIMED_SHOT => self::BOOLEAN,
        ];
    }

    public const ATTACK_WITH_WEAPON_TYPE = 'attack_with_weapon_type';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::ATTACK_WITH_WEAPON_TYPE];
    }

    /**
     * Gives a list of all possible actions with given weapon.
     * Note about SPEAR: that weapon can be used both for melee as well as ranged combat (throwing) - for this weapon
     * you will get merged pool of both melee and ranged actions.
     *
     * @param WeaponlikeCode $weaponlikeCode
     * @return array|string[]
     */
    public function getActionsPossibleWhenFightingWith(WeaponlikeCode $weaponlikeCode): array
    {
        $possibleActions = [];
        foreach ($this->getWeaponTypesByWeaponCode($weaponlikeCode) as $weaponType) {
            $possibleActions = array_unique(
                array_merge($possibleActions, $this->getActionsPossibleWithType($weaponType))
            );
        }

        return $possibleActions;
    }

    private function getActionsPossibleWithType($weaponType)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return array_keys(
            array_filter(
                $this->getRow($weaponType),
                function ($isAllowed) {
                    return $isAllowed; // directly uses value given by table source
                }
            )
        );
    }

    public const MELEE = 'melee';
    public const SHOOTING = 'shooting';
    public const THROWING = 'throwing';

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @return array|string[]
     */
    private function getWeaponTypesByWeaponCode(WeaponlikeCode $weaponlikeCode): array
    {
        $types = [];
        if ($weaponlikeCode->isMelee()) {
            $types[] = self::MELEE;
        }
        if ($weaponlikeCode->isShootingWeapon()) {
            $types[] = self::SHOOTING;
        }
        if ($weaponlikeCode->isThrowingWeapon()) {
            $types[] = self::THROWING;
        }

        return $types;
    }

}