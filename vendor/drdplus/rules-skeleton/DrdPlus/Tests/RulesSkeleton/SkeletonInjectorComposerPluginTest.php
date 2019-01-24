<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\SkeletonInjectorComposerPlugin;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class SkeletonInjectorComposerPluginTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Name_of_package_matches(): void
    {
        if (!$this->isRulesSkeletonChecked()) {
            self::assertTrue(true, 'Should be fine');

            return;
        }
        self::assertSame(SkeletonInjectorComposerPlugin::RULES_SKELETON_PACKAGE_NAME, $this->getComposerConfig()['name']);
    }

    /**
     * @test
     */
    public function Package_is_injected(): void
    {
        if (!$this->isRulesSkeletonChecked()) {
            self::assertFalse(false, 'Intended for skeleton only');

            return;
        }
        self::assertSame('composer-plugin', $this->getComposerConfig()['type']);
        self::assertSame(SkeletonInjectorComposerPlugin::class, $this->getComposerConfig()['extra']['class']);
        self::assertArrayHasKey('composer-plugin-api', $this->getComposerConfig()['require']);
    }
}
