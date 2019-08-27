<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

/**
 * @backupGlobals enabled
 */
class DevModeTest extends \DrdPlus\Tests\RulesSkeleton\DevModeTest
{
    use Partials\CalculatorContentTestTrait;

    /**
     * @test
     */
    public function I_see_content_marked_by_development_classes(): void
    {
        self::assertFalse(false, 'Intended for rules skeleton only');
    }
}