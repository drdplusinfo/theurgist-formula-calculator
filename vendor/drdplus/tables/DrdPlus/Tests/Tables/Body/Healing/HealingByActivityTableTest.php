<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Body\Healing;

use DrdPlus\Codes\Body\ActivityAffectingHealingCode;
use DrdPlus\Tables\Body\Healing\HealingByActivityTable;
use DrdPlus\Tests\Tables\TableTest;

class HealingByActivityTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        $healingBonusByPersonActionsTable = new HealingByActivityTable();
        self::assertSame([['situation', 'bonus']], $healingBonusByPersonActionsTable->getHeader());
    }

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        $healingByActivityTable = new HealingByActivityTable();
        self::assertSame(
            $this->assembleIndexedValues($this->provideBonusWithActivityName()),
            $healingByActivityTable->getIndexedValues()
        );
    }

    private function assembleIndexedValues(array $values)
    {
        $indexedValues = [];
        foreach ($values as [$bonus, $situation]) {
            $indexedValues[$situation] = ['bonus' => $bonus];
        }

        return $indexedValues;
    }

    /**
     * @test
     * @dataProvider provideBonusWithActivityName
     * @param int $expectedBonus
     * @param string $activityName
     */
    public function I_can_get_healing_bonus_for_every_activity($expectedBonus, $activityName)
    {
        $healingBonusByPersonActionsTable = new HealingByActivityTable();
        self::assertSame($expectedBonus, $healingBonusByPersonActionsTable->getHealingBonusByActivity($activityName));
    }

    public function provideBonusWithActivityName()
    {
        return [
            [0, ActivityAffectingHealingCode::SLEEPING_OR_REST_IN_BED],
            [-2, ActivityAffectingHealingCode::LOUNGING_AND_RESTING],
            [-4, ActivityAffectingHealingCode::LIGHT_ACTIVITY],
            [-6, ActivityAffectingHealingCode::NORMAL_ACTIVITY],
            [-8, ActivityAffectingHealingCode::TOILSOME_ACTIVITY],
            [-10, ActivityAffectingHealingCode::VERY_HARD_ACTIVITY],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Body\Healing\Exceptions\UnknownCodeOfHealingInfluence
     * @expectedExceptionMessageRegExp ~swimming_with_dolphins~
     */
    public function I_can_not_get_healing_bonus_for_unknown_activity()
    {
        (new HealingByActivityTable())->getHealingBonusByActivity('swimming_with_dolphins');
    }

}
