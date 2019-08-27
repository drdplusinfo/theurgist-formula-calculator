<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Web;

use DrdPlus\Tests\CalculatorSkeleton\Partials\CalculatorContentTestTrait;

class PassContentTest extends \DrdPlus\Tests\RulesSkeleton\Web\PassContentTest
{
    use CalculatorContentTestTrait;

    /**
     * @test
     */
    public function Headings_do_not_have_anchors_to_self(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(false, 'No pass here');

            return;
        }
        parent::Headings_do_not_have_anchors_to_self();
    }

    /**
     * @test
     */
    public function Pass_does_not_reset_requested_path_and_query(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(false, 'No pass here');

            return;
        }
        parent::Pass_does_not_reset_requested_path_and_query();
    }
}
