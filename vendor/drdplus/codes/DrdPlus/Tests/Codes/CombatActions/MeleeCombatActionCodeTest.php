<?php
namespace DrdPlus\Tests\Codes\CombatActions;

use DrdPlus\Codes\CombatActions\CombatActionCode;
use DrdPlus\Codes\CombatActions\MeleeCombatActionCode;
use DrdPlus\Tests\Codes\AbstractCodeTest;

class MeleeCombatActionCodeTest extends AbstractCodeTest
{

    /**
     * @test
     */
    public function I_can_get_melee_only_codes()
    {
        $expectedMeleeOnly = array_diff(
            array_values((new \ReflectionClass(MeleeCombatActionCode::class))->getConstants()),
            CombatActionCode::getPossibleValues()
        );
        self::assertSame($expectedMeleeOnly, MeleeCombatActionCode::getMeleeOnlyCombatActionValues());
    }

    /**
     * @test
     */
    public function Melee_only_codes_are_marked()
    {
        $reflection = new \ReflectionClass(MeleeCombatActionCode::class);
        foreach ($reflection->getConstants() as $name => $value) {
            $meleeCombatActionCode = MeleeCombatActionCode::getIt($value);
            self::assertTrue($meleeCombatActionCode->isForMelee());
            self::assertSame(
                defined(CombatActionCode::class . '::' . $name),
                $meleeCombatActionCode->isForRanged(),
                'Only constant defined in parent CombatActionCode should be usable for ranged attack'
            );
        }
    }
}