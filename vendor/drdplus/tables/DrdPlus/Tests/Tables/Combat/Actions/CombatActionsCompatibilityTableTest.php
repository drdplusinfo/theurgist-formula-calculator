<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Combat\Actions;

use DrdPlus\Codes\CombatActions\CombatActionCode;
use DrdPlus\Codes\CombatActions\MeleeCombatActionCode;
use DrdPlus\Codes\CombatActions\RangedCombatActionCode;
use DrdPlus\Tables\Combat\Actions\CombatActionsCompatibilityTable;
use DrdPlus\Tests\Tables\TableTest;
use Mockery\MockInterface;

class CombatActionsCompatibilityTableTest extends TableTest
{

    /**
     * @test
     */
    public function I_can_get_compatibility_to_all_combat_actions()
    {
        $actions = CombatActionCode::getPossibleValues();
        $actions = array_merge($actions, MeleeCombatActionCode::getMeleeOnlyCombatActionValues());
        $actions = array_merge($actions, RangedCombatActionCode::getRangedOnlyCombatActionValues());
        sort($actions);
        $compatibilities = (new CombatActionsCompatibilityTable())->getHeader()[0];
        array_shift($compatibilities); // remove rows header
        sort($compatibilities);
        self::assertSame(
            $actions,
            $compatibilities,
            'missing: ' . implode(',', array_diff($actions, $compatibilities))
            . "\n" . 'redundant: ' . implode(',', array_diff($compatibilities, $actions))
        );
    }

    /**
     * @test
     * @depends I_can_get_compatibility_to_all_combat_actions
     */
    public function Combinations_are_same_from_both_sides()
    {
        $codes = [];
        foreach (CombatActionCode::getPossibleValues() as $meleeOnlyCombatActionCode) {
            $codes[] = CombatActionCode::getIt($meleeOnlyCombatActionCode);
        }
        foreach (MeleeCombatActionCode::getMeleeOnlyCombatActionValues() as $meleeOnlyCombatActionCode) {
            $codes[] = MeleeCombatActionCode::getIt($meleeOnlyCombatActionCode);
        }
        foreach (RangedCombatActionCode::getRangedOnlyCombatActionValues() as $rangedOnlyCombatActionCode) {
            $codes[] = RangedCombatActionCode::getIt($rangedOnlyCombatActionCode);
        }
        $combatActionsCompatibilityTable = new CombatActionsCompatibilityTable();
        foreach ($codes as $someCode) {
            foreach ($codes as $anotherCode) {
                self::assertSame(
                    $combatActionsCompatibilityTable->getValue($someCode, $anotherCode),
                    $combatActionsCompatibilityTable->getValue($anotherCode, $someCode),
                    "'{$someCode}' x '{$anotherCode}' do not match from both sides"
                );
            }
        }
    }

    /**
     * @test
     */
    public function I_can_find_out_easily_if_two_actions_can_be_combined()
    {
        $combatActionsCompatibilityTable = new CombatActionsCompatibilityTable();
        self::assertTrue($combatActionsCompatibilityTable->canCombineTwoActions(
            CombatActionCode::getIt(CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY),
            CombatActionCode::getIt(CombatActionCode::MOVE)
        ));
        self::assertFalse($combatActionsCompatibilityTable->canCombineTwoActions(
            CombatActionCode::getIt(CombatActionCode::GETTING_UP),
            CombatActionCode::getIt(CombatActionCode::LAYING)
        ));
    }

    /**
     * @test
     */
    public function I_can_find_out_easily_if_any_actions_can_be_combined()
    {
        $combatActionsCompatibilityTable = new CombatActionsCompatibilityTable();
        self::assertTrue($combatActionsCompatibilityTable->canCombineActions(
            [MeleeCombatActionCode::getIt(MeleeCombatActionCode::COVER_OF_ALLY)]
        ));
        self::assertTrue($combatActionsCompatibilityTable->canCombineActions([
            CombatActionCode::getIt(CombatActionCode::MOVE),
            CombatActionCode::getIt(CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY),
            MeleeCombatActionCode::getIt(MeleeCombatActionCode::RETREAT),
        ]));
        self::assertFalse($combatActionsCompatibilityTable->canCombineActions([
            CombatActionCode::getIt(CombatActionCode::MOVE),
            CombatActionCode::getIt(CombatActionCode::FIGHT_IN_REDUCED_VISIBILITY),
            MeleeCombatActionCode::getIt(MeleeCombatActionCode::RETREAT),
            RangedCombatActionCode::getIt(RangedCombatActionCode::AIMED_SHOT),
        ]));
    }

    /**
     * @test
     * @dataProvider provideUnknownCombatActions
     * @param string $someAction
     * @param string $anotherAction
     */
    public function I_can_not_get_compatibility_of_unknown_actions(string $someAction, string $anotherAction)
    {
        $this->expectException(\DrdPlus\Tables\Combat\Actions\Exceptions\UnknownCombatAction::class);
        $someActionCode = $this->createCombatActionCode($someAction);
        $anotherActionCode = $this->createCombatActionCode($anotherAction);
        (new CombatActionsCompatibilityTable())->canCombineTwoActions($someActionCode, $anotherActionCode);
    }

    /**
     * @param string $value
     * @return CombatActionCode|MockInterface
     */
    private function createCombatActionCode(string $value): CombatActionCode
    {
        $combatActionCode = $this->mockery(CombatActionCode::class);
        $combatActionCode->shouldReceive('getValue')
            ->andReturn($value);
        $combatActionCode->shouldReceive('__toString')
            ->andReturn($value);

        return $combatActionCode;
    }

    public function provideUnknownCombatActions(): array
    {
        return [
            [CombatActionCode::ATTACK_ON_DISABLED_OPPONENT, 'foo'],
            ['bar', CombatActionCode::HANDOVER_ITEM],
        ];
    }
}