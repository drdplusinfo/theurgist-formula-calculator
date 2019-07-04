<?php declare(strict_types=1);

namespace Granam\Tests\WebContentBuilder;

use Granam\WebContentBuilder\Dirs;
use Granam\Tests\WebContentBuilder\Partials\AbstractContentTest;

class DirsTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_use_it(): void
    {
        $dirsClass = static::getSutClass();
        /** @var Dirs $dirs */
        $dirs = new $dirsClass('foo');
        self::assertSame('foo', $dirs->getProjectRoot());
        self::assertSame('foo/web', $dirs->getWebRoot());
        self::assertSame('foo/vendor', $dirs->getVendorRoot());
        self::assertSame('foo/css', $dirs->getCssRoot());
        self::assertSame('foo/js', $dirs->getJsRoot());
    }

    /**
     * @test
     * @backupGlobals enabled
     * @dataProvider provideProjectRoots
     * @param string $serverProjectDir
     * @param string $serverDocumentRoot
     * @param string $testingCwd
     * @param string $expectedDocumentRoot
     */
    public function I_can_create_it_from_globals(
        ?string $serverProjectDir,
        ?string $serverDocumentRoot,
        string $testingCwd,
        string $expectedDocumentRoot
    ): void
    {
        $originalCwd = getcwd();
        $_SERVER['PROJECT_DIR'] = $serverProjectDir;
        $_SERVER['DOCUMENT_ROOT'] = $serverDocumentRoot;
        if ($originalCwd !== $testingCwd) {
            chdir($testingCwd);
        }
        $dirs = Dirs::createFromGlobals();
        $resultingDocumentRoot = $dirs->getProjectRoot();
        if ($originalCwd !== $testingCwd) {
            chdir($originalCwd);
        }
        self::assertSame($expectedDocumentRoot, $resultingDocumentRoot);
    }

    public function provideProjectRoots(): array
    {
        return [
            ['SERVER_PROJECT_DIR' => null, 'SERVER_DOCUMENT_ROOT' => '', 'cwd' => __DIR__, __DIR__],
            ['SERVER_PROJECT_DIR' => '', 'SERVER_DOCUMENT_ROOT' => '', 'cwd' => __DIR__, __DIR__],
            ['SERVER_PROJECT_DIR' => null, 'SERVER_DOCUMENT_ROOT' => null, 'cwd' => __DIR__, __DIR__],
            ['SERVER_PROJECT_DIR' => null, 'SERVER_DOCUMENT_ROOT' => 'foo', 'cwd' => __DIR__, 'foo'],
            ['SERVER_PROJECT_DIR' => 'bar', 'SERVER_DOCUMENT_ROOT' => 'foo', 'cwd' => __DIR__, 'bar'],
        ];
    }

    /**
     * @test
     */
    public function I_will_get_current_root_as_default_project_root(): void
    {
        $expectedProjectRoot = \realpath($this->getProjectRoot());
        self::assertFileExists($expectedProjectRoot, 'No real path found from project root ' . $this->getProjectRoot());
        self::assertSame($expectedProjectRoot, \realpath($this->getDirs()->getProjectRoot()));
    }
}