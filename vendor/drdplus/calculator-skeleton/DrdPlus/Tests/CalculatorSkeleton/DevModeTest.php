<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class DevModeTest extends \DrdPlus\Tests\RulesSkeleton\DevModeTest
{
    use Partials\AbstractContentTestTrait;

    /**
     * @test
     */
    public function I_see_content_marked_by_development_classes(): void
    {
        self::assertFalse(false, 'Intended for rules skeleton only');
    }
}