<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\InjectorComposerPlugin;

use Composer\Plugin\PluginInterface;
use DrdPlus\RulesSkeleton\InjectorComposerPlugin\SkeletonInjectorComposerPlugin;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class SkeletonInjectorComposerPluginTest extends AbstractContentTest
{
    public function Composer_package_is_available(): void
    {
        self::assertTrue(
            \interface_exists(PluginInterface::class),
            "Composer package is required for this test, include it by\ncomposer require --dev composer/composer"
        );
    }

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

    /**
     * @test
     */
    public function Injection_works(): void
    {
        if (!$this->isRulesSkeletonChecked()) {
            self::assertFalse(false, 'Intended for skeleton only');
            return;
        }
        $testDir = sys_get_temp_dir() . '/' . uniqid(basename(__FILE__, '.php'), true);
        self::assertTrue(
            @mkdir($testDir, 0755, true) || is_dir($testDir),
            "Can not create dir $testDir"
        );

        exec(sprintf('cd %s 2>&1', escapeshellarg($testDir)), $output, $returnCode);
        self::assertSame(
            0,
            $returnCode,
            'Something goes wrong ' . implode(',', $output)
        );

        $skeletonRootDir = $this->getProjectRoot();
        $escapedSkeletonRootDir = json_encode($skeletonRootDir, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents(
            $testDir . '/composer.json',
            <<<JSON
{
  "require": {
    "drdplus/rules-skeleton": "dev-master"
  },
  "repositories": [
    {
        "url": $escapedSkeletonRootDir,
        "type": "git"
    }
  ]
}
JSON
        );

        $testDirEscaped = escapeshellarg($testDir);

        // favicon should be overwritten
        exec("touch $testDirEscaped/favicon.ico 2>&1", $output, $returnCode);
        self::assertSame(
            0,
            $returnCode,
            'Something goes wrong ' . implode("\n", $output)
        );

        exec("cd $testDirEscaped && composer require drdplus/rules-skeleton:dev-master 2>&1 && ls -al images", $output, $returnCode);
        self::assertSame(
            0,
            $returnCode,
            'Something goes wrong ' . implode("\n", $output)
        );

        $this->projectConfigIsCopied($testDir);
        $this->cacheDirIsCreatedWithGitIgnore($testDir);
        $this->faviconIsCopied($testDir);
        $this->routesArePopulated($testDir);
        $this->indexIsPopulated($testDir);
        $this->webDirIsCreated($testDir);
    }

    private function projectConfigIsCopied(string $dir)
    {
        $projectConfigFile = $this->getProjectRoot() . '/config.distribution.yml';
        self::assertFileExists($projectConfigFile);

        $copiedProjectConfigFile = $dir . '/config.distribution.yml';

        self::assertFileExists($copiedProjectConfigFile);
        self::assertFileEquals($projectConfigFile, $copiedProjectConfigFile);
    }

    private function cacheDirIsCreatedWithGitIgnore(string $dir)
    {
        $cacheDir = $dir . '/cache';
        self::assertDirectoryExists($cacheDir);
        self::assertDirectoryIsWritable($cacheDir);

        $cacheGitIgnoreFile = $cacheDir . '/.gitignore';
        self::assertFileExists($cacheGitIgnoreFile);
        self::assertSame(<<<TEXT
*
!/.gitignore
TEXT
            , file_get_contents($cacheGitIgnoreFile)
        );
    }

    private function faviconIsCopied(string $dir)
    {
        $faviconFile = $this->getProjectRoot() . '/favicon.ico';
        self::assertFileExists($faviconFile);
        self::assertNotSame(0, filesize($faviconFile));

        $copiedFaviconFile = $dir . '/favicon.ico';

        self::assertFileExists($copiedFaviconFile);
        self::assertFileEquals($faviconFile, $copiedFaviconFile);
    }

    private function routesArePopulated(string $dir)
    {
        $routesFile = $this->getProjectRoot() . '/routes.yml';
        self::assertFileExists($routesFile);

        $copiedRoutesFile = $dir . '/routes.yml';

        self::assertFileExists($copiedRoutesFile);
        self::assertFileEquals($routesFile, $copiedRoutesFile);
    }

    private function indexIsPopulated(string $dir)
    {
        $indexFile = $dir . '/index.php';

        self::assertFileExists($indexFile);
        self::assertSame(
            <<<'PHP'
<?php
require __DIR__ . '/vendor/drdplus/rules-skeleton/index.php';

PHP
            ,
            file_get_contents($indexFile),
            "Copied index $indexFile does not match expected content"
        );
    }

    private function webDirIsCreated(string $dir)
    {
        $webDir = $dir . '/web';

        self::assertDirectoryExists($webDir);
        self::assertDirectoryIsWritable($webDir);
    }
}
