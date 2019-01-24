<?php
declare(strict_types=1);

namespace Granam\Tests\WebContentBuilder\Partials;

use Granam\Tests\Tools\TestWithMockery;
use Granam\WebContentBuilder\Dirs;
use Granam\WebContentBuilder\HtmlHelper;
use Granam\WebContentBuilder\Request;
use Gt\Dom\Element;
use Gt\Dom\HTMLDocument;

abstract class AbstractContentTest extends TestWithMockery
{
    /** @var Dirs */
    private $dirs;

    /**
     * @param Dirs $dirs
     * @return HtmlHelper|\Mockery\MockInterface
     */
    protected function createHtmlHelper(Dirs $dirs = null): HtmlHelper
    {
        return new HtmlHelper($dirs ?? $this->getDirs());
    }

    protected function getHtmlHelper(): HtmlHelper
    {
        static $htmlHelper;
        if ($htmlHelper === null) {
            $htmlHelper = $this->createHtmlHelper();
        }

        return $htmlHelper;
    }

    protected function runCommand(string $command): array
    {
        \exec("$command 2>&1", $output, $returnCode);
        self::assertSame(0, $returnCode, "Failed command '$command', got output " . \var_export($output, true));

        return $output;
    }

    protected function executeCommand(string $command): string
    {
        $output = $this->runCommand($command);

        return \end($output) ?: '';
    }

    /**
     * @param HtmlDocument $document
     * @return array|Element[]
     */
    protected function getMetaRefreshes(HtmlDocument $document): array
    {
        $metaElements = $document->head->getElementsByTagName('meta');
        $metaRefreshes = [];
        foreach ($metaElements as $metaElement) {
            if ($metaElement->getAttribute('http-equiv') === 'Refresh') {
                $metaRefreshes[] = $metaElement;
            }
        }

        return $metaRefreshes;
    }

    protected function createRequest(string $currentVersion = null): Request
    {
        $request = $this->mockery($this->getRequestClass());
        $request->allows('getValue')
            ->with(Request::VERSION)
            ->andReturn($currentVersion);
        $request->makePartial();

        /** @var Request $request */
        return $request;
    }

    protected function getRequestClass(): string
    {
        return Request::class;
    }

    protected function isLibraryChecked(): bool
    {
        static $libraryChecked;
        if ($libraryChecked === null) {
            $projectRootRealPath = \realpath($this->getProjectRoot());
            self::assertNotEmpty($projectRootRealPath, 'Can not find out real path of project root ' . \var_export($this->getProjectRoot(), true));
            $libraryDocumentRootRealPath = \realpath(__DIR__ . '/../../../..');
            self::assertNotEmpty($libraryDocumentRootRealPath, 'Can not find out real path of library root ' . \var_export($libraryDocumentRootRealPath, true));
            self::assertRegExp('~^web-content-builder$~', \basename($libraryDocumentRootRealPath), 'Expected different trailing dir of web content builder project root');

            $libraryChecked = $projectRootRealPath === $libraryDocumentRootRealPath;
        }

        return $libraryChecked;
    }

    protected function getDirs(): Dirs
    {
        if (!$this->dirs) {
            $this->dirs = Dirs::createFromGlobals();
        }

        return $this->dirs;
    }

    protected function getProjectRoot(): string
    {
        return $this->getDirs()->getProjectRoot();
    }

    protected function unifyPath(string $path): string
    {
        $path = \str_replace('\\', '/', $path);
        $path = \preg_replace('~/\.(?:/|$)~', '/', $path);

        return $this->squashTwoDots($path);
    }

    private function squashTwoDots(string $path): string
    {
        $originalPath = $path;
        $path = \preg_replace('~/[^/.]+/\.\.~', '', $path);
        if ($originalPath === $path) {
            return $originalPath; // nothing has been squashed
        }

        return $this->squashTwoDots($path);
    }
}