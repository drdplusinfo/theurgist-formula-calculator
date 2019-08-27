<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorInjectorComposerPlugin;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractCalculatorContentTest;

class CalculatorInjectorComposerPluginTest extends AbstractCalculatorContentTest
{
    /**
     * @test
     */
    public function Name_of_package_matches(): void
    {
        if (!$this->isCalculatorSkeletonChecked()) {
            self::assertTrue(true, 'Should be fine');

            return;
        }
        self::assertSame(CalculatorInjectorComposerPlugin::CALCULATOR_SKELETON_PACKAGE_NAME, $this->getComposerConfig()['name']);
    }

    /**
     * @test
     */
    public function Package_is_injected(): void
    {
        if (!$this->isCalculatorSkeletonChecked()) {
            self::assertFalse(false, 'Intended for skeleton only');

            return;
        }
        self::assertSame('composer-plugin', $this->getComposerConfig()['type']);
        self::assertSame(CalculatorInjectorComposerPlugin::class, $this->getComposerConfig()['extra']['class']);
        self::assertArrayHasKey('composer-plugin-api', $this->getComposerConfig()['require']);
    }
}
