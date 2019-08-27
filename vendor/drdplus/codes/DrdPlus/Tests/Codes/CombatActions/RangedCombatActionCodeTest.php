<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\CombatActions;

use DrdPlus\Codes\CombatActions\CombatActionCode;
use DrdPlus\Codes\CombatActions\RangedCombatActionCode;
use DrdPlus\Tests\Codes\AbstractCodeTest;

class RangedCombatActionCodeTest extends AbstractCodeTest
{
    /**
     * @test
     */
    public function I_can_get_ranged_only_codes()
    {
        $expectedMeleeOnly = array_diff(
            array_values((new \ReflectionClass(RangedCombatActionCode::class))->getConstants()),
            CombatActionCode::getPossibleValues()
        );
        self::assertSame($expectedMeleeOnly, RangedCombatActionCode::getRangedOnlyCombatActionValues());
    }

    /**
     * @test
     */
    public function Ranged_only_codes_are_marked_so()
    {
        $reflection = new \ReflectionClass(RangedCombatActionCode::class);
        foreach ($reflection->getConstants() as $name => $value) {
            $rangedCombatActionCode = RangedCombatActionCode::getIt($value);
            self::assertTrue($rangedCombatActionCode->isForRanged());
            self::assertSame(
                defined(CombatActionCode::class . '::' . $name),
                $rangedCombatActionCode->isForMelee(),
                'Only constant defined in parent CombatActionCode should be usable for melee attack'
            );
        }
    }

}