<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class StandardModeTest extends \DrdPlus\Tests\RulesSkeleton\StandardModeTest
{
    use Partials\CalculatorContentTestTrait;

    /**
     * @test
     */
    public function I_get_notes_styled(): void
    {
        if (!$this->getTestsConfiguration()->hasNotes()) {
            self::assertFalse(false, 'No tables in calculator');

            return;
        }
        parent::I_get_notes_styled();
    }

}