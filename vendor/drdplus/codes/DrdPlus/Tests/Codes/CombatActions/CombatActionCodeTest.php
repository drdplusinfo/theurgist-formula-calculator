<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\CombatActions;

use DrdPlus\Codes\CombatActions\CombatActionCode;
use DrdPlus\Tests\Codes\AbstractCodeTest;

class CombatActionCodeTest extends AbstractCodeTest
{

    /**
     * @test
     */
    public function It_is_both_for_melee_and_ranged()
    {
        self::assertTrue(CombatActionCode::getIt(CombatActionCode::BLINDFOLD_FIGHT)->isForMelee());
        self::assertTrue(CombatActionCode::getIt(CombatActionCode::CONCENTRATION_ON_DEFENSE)->isForRanged());
    }

}