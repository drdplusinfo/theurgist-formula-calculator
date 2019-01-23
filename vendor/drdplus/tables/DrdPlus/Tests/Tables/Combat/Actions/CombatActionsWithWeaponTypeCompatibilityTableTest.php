<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Combat\Actions;

use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\CombatActions\CombatActionCode;
use DrdPlus\Codes\CombatActions\MeleeCombatActionCode;
use DrdPlus\Codes\CombatActions\RangedCombatActionCode;
use DrdPlus\Tables\Combat\Actions\CombatActionsCompatibilityTable;
use DrdPlus\Tables\Combat\Actions\CombatActionsWithWeaponTypeCompatibilityTable;
use DrdPlus\Tests\Tables\TableTest;

class CombatActionsWithWeaponTypeCompatibilityTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            $expected = [
                [
                    'attack_with_weapon_type',
                    'move',
                    'run',
                    'swap_weapons',
                    'concentration_on_defense',
                    'put_out_easily_accessible_item',
                    'put_out_hardly_accessible_item',
                    'laying',
                    'sitting_or_on_kneels',
                    'getting_up',
                    'putting_on_armor',
                    'putting_on_armor_with_help',
                    'helping_to_put_on_armor',
                    'attacked_from_behind',
                    'blindfold_fight',
                    'fight_in_reduced_visibility',
                    'attack_on_disabled_opponent',
                    'handover_item',
                    'headless_attack',
                    'cover_of_ally',
                    'flat_attack',
                    'pressure',
                    'retreat',
                    'aimed_shot',
                ],
            ],
            $current = (new CombatActionsWithWeaponTypeCompatibilityTable())->getHeader(),
            implode(',', array_diff($expected[0], $current[0]))
        );
    }

    /**
     * @test
     */
    public function Pool_of_actions_is_same_as_from_combat_actions_compatibility_table()
    {
        $current = (new CombatActionsWithWeaponTypeCompatibilityTable())->getHeader()[0];
        array_shift($current); // remove rows header name
        sort($current);
        $shouldBe = (new CombatActionsCompatibilityTable())->getHeader()[0];
        array_shift($shouldBe); // remove rows header name
        sort($shouldBe);
        self::assertSame(
            $current,
            $shouldBe,
            implode(',', array_diff($current, $shouldBe)) . ';' . implode(',', array_diff($shouldBe, $current))
        );
    }

    /**
     * @test
     * @dataProvider provideWeaponUsageWithExpectedActions
     * @param bool $isMelee
     * @param bool $isThrowing
     * @param bool $isShooting
     * @param array|string[] $expectedActions
     */
    public function I_can_get_actions_possible_when_fighting_with_weapon(
        $isMelee,
        $isThrowing,
        $isShooting,
        array $expectedActions
    )
    {
        $weaponlikeCode = $this->createWeaponlikeCode($isMelee, $isThrowing, $isShooting);
        $table = new CombatActionsWithWeaponTypeCompatibilityTable();
        self::assertSame(
            $expectedActions = self::sort($expectedActions),
            $possibleActions = self::sort($table->getActionsPossibleWhenFightingWith($weaponlikeCode)),
            'Differences: ' . implode(
                ' and ',
                [
                    implode(',', array_diff($expectedActions, $possibleActions)),
                    implode(',', array_diff($possibleActions, $expectedActions)),
                ]
            )
        );
    }

    private static function sort(array $array)
    {
        sort($array);

        return $array;
    }

    public function provideWeaponUsageWithExpectedActions()
    {
        return [
            // melee, throwing, shooting, two handed, one handed, actions
            [false, false, false, []],
            [false, true, false, self::getThrowingWeaponActions()],
            [false, false, true, self::getShootingWeaponActions()],
            // weapon usable both as one or two handed has same actions as one handed (because it is not "only" one handed)
            [true, false, false, self::getMeleeActions()],
            [true, false, false, self::getOnlyOneHandedMeleeWeaponActions()],
            [true, false, false, self::getOnlyTwoHandedMeleeWeaponActions()],
            [true, true, false, self::getOnlyOneHandedMeleeAndThrowingWeaponActions()], // like spear
            [true, true, true, self::getTotallyUniversallyWeaponActions()] // like ... eee ... evolution?
        ];
    }

    private static function getMeleeActions()
    {
        return [
            CombatActionCode::MOVE,
            CombatActionCode::LAYING,
            CombatActionCode::SITTING_OR_ON_KNEELS,
            CombatActionCode::ATTACKED_FROM_BEHIND,
            CombatActionCode::BLINDFOLD_FIGHT,
            CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY,
            CombatActionCode::ATTACK_ON_DISABLED_OPPONENT,
            CombatActionCode::HANDOVER_ITEM,
            MeleeCombatActionCode::HEADLESS_ATTACK,
            MeleeCombatActionCode::FLAT_ATTACK,
            MeleeCombatActionCode::PRESSURE,
        ];
    }

    private static function getThrowingWeaponActions()
    {
        return [
            CombatActionCode::MOVE,
            CombatActionCode::LAYING,
            CombatActionCode::SITTING_OR_ON_KNEELS,
            CombatActionCode::ATTACKED_FROM_BEHIND,
            CombatActionCode::BLINDFOLD_FIGHT,
            CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY,
            CombatActionCode::ATTACK_ON_DISABLED_OPPONENT,
            CombatActionCode::HANDOVER_ITEM,
        ];
    }

    private static function getShootingWeaponActions()
    {
        return [
            CombatActionCode::MOVE,
            CombatActionCode::LAYING,
            CombatActionCode::SITTING_OR_ON_KNEELS,
            CombatActionCode::ATTACKED_FROM_BEHIND,
            CombatActionCode::BLINDFOLD_FIGHT,
            CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY,
            CombatActionCode::ATTACK_ON_DISABLED_OPPONENT,
            CombatActionCode::HANDOVER_ITEM,
            RangedCombatActionCode::AIMED_SHOT,
        ];
    }

    private static function getOnlyOneHandedMeleeWeaponActions()
    {
        return [
            CombatActionCode::MOVE,
            CombatActionCode::LAYING,
            CombatActionCode::SITTING_OR_ON_KNEELS,
            CombatActionCode::ATTACKED_FROM_BEHIND,
            CombatActionCode::BLINDFOLD_FIGHT,
            CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY,
            CombatActionCode::ATTACK_ON_DISABLED_OPPONENT,
            CombatActionCode::HANDOVER_ITEM,
            MeleeCombatActionCode::HEADLESS_ATTACK,
            MeleeCombatActionCode::FLAT_ATTACK,
            MeleeCombatActionCode::PRESSURE,
        ];
    }

    private static function getOnlyTwoHandedMeleeWeaponActions()
    {
        return [
            CombatActionCode::MOVE,
            CombatActionCode::LAYING,
            CombatActionCode::SITTING_OR_ON_KNEELS,
            CombatActionCode::ATTACKED_FROM_BEHIND,
            CombatActionCode::BLINDFOLD_FIGHT,
            CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY,
            CombatActionCode::ATTACK_ON_DISABLED_OPPONENT,
            CombatActionCode::HANDOVER_ITEM,
            MeleeCombatActionCode::HEADLESS_ATTACK,
            MeleeCombatActionCode::FLAT_ATTACK,
            MeleeCombatActionCode::PRESSURE,
        ];
    }

    private static function getOnlyOneHandedMeleeAndThrowingWeaponActions()
    {
        return [
            CombatActionCode::MOVE,
            CombatActionCode::LAYING,
            CombatActionCode::SITTING_OR_ON_KNEELS,
            CombatActionCode::ATTACKED_FROM_BEHIND,
            CombatActionCode::BLINDFOLD_FIGHT,
            CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY,
            CombatActionCode::ATTACK_ON_DISABLED_OPPONENT,
            CombatActionCode::HANDOVER_ITEM,
            MeleeCombatActionCode::HEADLESS_ATTACK,
            MeleeCombatActionCode::FLAT_ATTACK,
            MeleeCombatActionCode::PRESSURE,
        ];
    }

    private static function getTotallyUniversallyWeaponActions()
    {
        return [
            CombatActionCode::MOVE,
            CombatActionCode::LAYING,
            CombatActionCode::SITTING_OR_ON_KNEELS,
            CombatActionCode::ATTACKED_FROM_BEHIND,
            CombatActionCode::BLINDFOLD_FIGHT,
            CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY,
            CombatActionCode::ATTACK_ON_DISABLED_OPPONENT,
            CombatActionCode::HANDOVER_ITEM,
            MeleeCombatActionCode::HEADLESS_ATTACK,
            MeleeCombatActionCode::FLAT_ATTACK,
            MeleeCombatActionCode::PRESSURE,
            RangedCombatActionCode::AIMED_SHOT,
        ];
    }

    /**
     * @param bool $isMelee
     * @param bool $isThrowingWeapon
     * @param bool $isShootingWeapon
     * @return \Mockery\MockInterface|WeaponlikeCode
     */
    private function createWeaponlikeCode($isMelee, $isThrowingWeapon, $isShootingWeapon)
    {
        $weaponLikeCode = $this->mockery(WeaponlikeCode::class);
        $weaponLikeCode->shouldReceive('isMelee')
            ->andReturn($isMelee);
        $weaponLikeCode->shouldReceive('isThrowingWeapon')
            ->andReturn($isThrowingWeapon);
        $weaponLikeCode->shouldReceive('isShootingWeapon')
            ->andReturn($isShootingWeapon);

        return $weaponLikeCode;
    }
}