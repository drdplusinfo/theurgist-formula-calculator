<?php
namespace DrdPlus\Tests\Codes\Body;

use DrdPlus\Codes\Body\ConditionsAffectingHealingCode;
use DrdPlus\Tests\Codes\AbstractCodeTest;

class ConditionsAffectingHealingCodeTest extends AbstractCodeTest
{
    /**
     * @test
     */
    public function I_will_get_conditions_from_best_to_worst(): void
    {
        self::assertSame(
            [
                ConditionsAffectingHealingCode::GOOD_CONDITIONS,
                ConditionsAffectingHealingCode::IMPAIRED_CONDITIONS,
                ConditionsAffectingHealingCode::BAD_CONDITIONS,
                ConditionsAffectingHealingCode::FOUL_CONDITIONS,
            ],
            ConditionsAffectingHealingCode::getPossibleValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_it_with_default_value(): void
    {
        $sut = $this->findSut();
        self::assertSame(ConditionsAffectingHealingCode::GOOD_CONDITIONS, $sut->getValue(), 'Expected good conditions as a default value');
    }
}