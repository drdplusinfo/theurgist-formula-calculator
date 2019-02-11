<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class CalculationsTest extends \DrdPlus\Tests\RulesSkeleton\CalculationsTest
{
    use Partials\CalculatorContentTestTrait;

    /**
     * @test
     */
    public function Calculation_has_descriptive_name(): void
    {
        $tooShortResultNames = $this->getTestsConfiguration()->getTooShortResultNames();
        if (!$tooShortResultNames) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        parent::Calculation_has_descriptive_name();
    }

    /**
     * @test
     */
    public function Result_content_trap_has_descriptive_name(): void
    {
        $tooShortFailureNames = $this->getTestsConfiguration()->getTooShortFailureNames();
        $tooShortSuccessNames = $this->getTestsConfiguration()->getTooShortSuccessNames();
        if (!$tooShortFailureNames && !$tooShortSuccessNames) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        parent::Result_content_trap_has_descriptive_name();
    }
}