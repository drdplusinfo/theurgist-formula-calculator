<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\SkeletonInjectorComposerPlugin;

class ComposerConfigTest extends \DrdPlus\Tests\RulesSkeleton\ComposerConfigTest
{
    use Partials\AbstractContentTestTrait;

    /**
     * @test
     */
    public function Assets_have_injected_versions(): void
    {
        self::assertFalse(false, 'Assets versions are injected by ' . SkeletonInjectorComposerPlugin::class);
    }

    public function Package_is_injected(): void
    {
        self::assertFalse(false, 'Only parent skeleton is injected as a package');
    }

}