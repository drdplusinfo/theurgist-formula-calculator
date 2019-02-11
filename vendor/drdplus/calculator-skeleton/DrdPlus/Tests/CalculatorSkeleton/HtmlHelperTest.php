<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;

class HtmlHelperTest extends \DrdPlus\Tests\RulesSkeleton\HtmlHelperTest
{
    use Partials\CalculatorContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return HtmlHelper::class;
    }

    /**
     * @test
     */
    public function I_can_get_filtered_tables_from_content(): void
    {
        if (!$this->getTestsConfiguration()->hasTables()) {
            self::assertFalse(false, 'Calculator does not have tables');

            return;
        }
        parent::I_can_get_filtered_tables_from_content();
    }

    /**
     * @test
     */
    public function Same_table_ids_are_filtered_on_tables_only_mode(): void
    {
        if (!$this->getTestsConfiguration()->hasTables()) {
            self::assertFalse(false, 'Calculator does not have tables');

            return;
        }
        parent::Same_table_ids_are_filtered_on_tables_only_mode();
    }

}