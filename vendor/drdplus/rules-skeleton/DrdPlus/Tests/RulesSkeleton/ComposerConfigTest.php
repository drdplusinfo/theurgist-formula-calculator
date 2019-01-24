<?php
namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\SkeletonInjectorComposerPlugin;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use DrdPlus\Tests\RulesSkeleton\Partials\TestsConfigurationReader;

/**
 * @method TestsConfigurationReader getTestsConfiguration
 */
class ComposerConfigTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Project_is_using_php_of_version_with_nullable_type_hints(): void
    {
        $requiredPhpVersion = $this->getComposerConfig()['require']['php'];
        self::assertGreaterThan(0, \preg_match('~(?<version>\d.+)$~', $requiredPhpVersion, $matches));
        $minimalPhpVersion = $matches['version'];
        self::assertGreaterThanOrEqual(
            0,
            \version_compare($minimalPhpVersion, '7.1'), "Required PHP version should be equal or greater to 7.1, get $requiredPhpVersion"
        );
    }

    /**
     * @test
     */
    public function Assets_have_injected_versions(): void
    {
        if (!$this->isSkeletonChecked()) {
            self::assertFalse(false, 'Assets versions are injected by ' . SkeletonInjectorComposerPlugin::class);

            return;
        }
        $postInstallScripts = $this->getComposerConfig()['scripts']['post-install-cmd'] ?? [];
        self::assertNotEmpty(
            $postInstallScripts,
            'Missing post-install-cmd scripts, expected at least "php vendor/bin/assets --css --dir=css"'
        );
        $postUpdateScripts = $this->getComposerConfig()['scripts']['post-update-cmd'] ?? [];
        self::assertNotEmpty(
            $postUpdateScripts,
            'Missing post-update-cmd scripts, expected at least "php vendor/bin/assets --css --dir=css"'
        );
        foreach ([$postInstallScripts, $postUpdateScripts] as $postChangeScripts) {
            self::assertContains(
                'php vendor/bin/assets --css --dir=css',
                $postChangeScripts,
                'Missing script to compile assets, there are only scripts '
                . \preg_replace('~^Array\n\((.+)\)~', '$1', \var_export($postChangeScripts, true))
            );
        }
    }

    /**
     * @test
     */
    public function Has_licence_matching_to_access(): void
    {
        $expectedLicence = $this->getTestsConfiguration()->getExpectedLicence();
        self::assertSame($expectedLicence, $this->getComposerConfig()['license'], "Expected licence '$expectedLicence'");
    }
}