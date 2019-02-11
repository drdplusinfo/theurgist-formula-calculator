<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class TablesTest extends \DrdPlus\Tests\RulesSkeleton\TablesTest
{
    use Partials\CalculatorContentTestTrait;

    /**
     * @test
     */
    public function I_can_get_tables_only(): void
    {
        if (!$this->getTestsConfiguration()->hasTables()) {
            self::assertFalse(false, 'No tables expected in calculator');

            return;
        }
        parent::I_can_get_tables_only();
    }

    /**
     * @test
     */
    public function I_can_get_wanted_tables_from_content(): void
    {
        if (!$this->getTestsConfiguration()->hasTables()) {
            self::assertFalse(false, 'Calculator does not have tables');

            return;
        }
        parent::I_can_get_wanted_tables_from_content();
    }
}