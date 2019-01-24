<?php
namespace Granam\Tests\WebContentBuilder;

use Granam\WebContentBuilder\WebContentBuilderInjectorComposerPlugin;
use Granam\Tests\WebContentBuilder\Partials\AbstractContentTest;

class WebContentBuilderInjectorComposerPluginTest extends AbstractContentTest
{
    protected static $composerConfig;

    protected function setUp(): void
    {
        parent::setUp();
        if (static::$composerConfig === null) {
            $composerFilePath = $this->getProjectRoot() . '/composer.json';
            self::assertFileExists($composerFilePath, 'composer.json has not been found in project root');
            $content = \file_get_contents($composerFilePath);
            self::assertNotEmpty($content, "Nothing has been fetched from $composerFilePath, is readable?");
            static::$composerConfig = \json_decode($content, true /*as array */);
            self::assertNotEmpty(static::$composerConfig, 'Can not decode composer.json content');
        }
    }

    /**
     * @test
     */
    public function Project_is_using_php_of_version_with_nullable_type_hints(): void
    {
        $requiredPhpVersion = static::$composerConfig['require']['php'];
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
    public function Package_is_injected(): void
    {
        if (!$this->isLibraryChecked()) {
            self::assertFalse(false, 'Intended for library only');

            return;
        }
        self::assertSame('composer-plugin', static::$composerConfig['type'], 'This library should be defined as composer plugin to allow its injection');
        self::assertSame(WebContentBuilderInjectorComposerPlugin::class, static::$composerConfig['extra']['class'], 'Expected current injector');
        self::assertArrayHasKey('composer-plugin-api', static::$composerConfig['require'], 'Injection requires composer plugin API package to works');
    }
}