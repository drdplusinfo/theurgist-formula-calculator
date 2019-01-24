<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class PassingTest extends \DrdPlus\Tests\RulesSkeleton\PassingTest
{
    use Partials\AbstractContentTestTrait;

    /**
     * @test
     * @backupGlobals enabled
     */
    public function Crawlers_can_pass_without_licence_owning_confirmation(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertTrue(true, 'Crawlers can access content as anyone else');

            return;
        }
        parent::Crawlers_can_pass_without_licence_owning_confirmation();
    }

    /**
     * @test
     */
    public function I_can_confirm_ownership(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(false, 'Calculator is free for all');

            return;
        }
        parent::I_can_confirm_ownership();
    }
}