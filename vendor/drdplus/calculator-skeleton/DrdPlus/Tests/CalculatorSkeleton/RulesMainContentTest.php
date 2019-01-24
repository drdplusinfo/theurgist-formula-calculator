<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class RulesMainContentTest extends \DrdPlus\Tests\RulesSkeleton\Web\RulesMainContentTest
{
    use Partials\AbstractContentTestTrait;

    /**
     * @test
     */
    public function Authors_got_heading(): void
    {
        if (!$this->getTestsConfiguration()->hasAuthors()) {
            self::assertFalse(false, 'Calculator does not have rules authors');

            return;
        }
        parent::Authors_got_heading();
    }

    /**
     * @test
     */
    public function Authors_are_mentioned(): void
    {
        if (!$this->getTestsConfiguration()->hasAuthors()) {
            self::assertFalse(false, 'Calculator does not have rules authors');

            return;
        }
        parent::Authors_are_mentioned();
    }
}