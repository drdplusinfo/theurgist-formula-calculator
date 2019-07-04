<?php declare(strict_types=1);

namespace Granam\WebContentBuilder\Web;

use Granam\WebContentBuilder\Dirs;

class CssFiles extends AbstractPublicFiles
{
    /**
     * @var string
     */
    private $cssRoot;

    public function __construct(Dirs $dirs, bool $preferMinified)
    {
        parent::__construct($preferMinified);
        $this->cssRoot = \rtrim($dirs->getCssRoot(), '\/');
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->scanForCssFiles($this->cssRoot));
    }

    /**
     * @param string $directory
     * @param string $cssRelativeRoot
     * @param int $level
     * @return array|string[]|string[][]
     */
    private function scanForCssFiles(string $directory, string $cssRelativeRoot = '', int $level = 1): array
    {
        $cssRelativeRoot = \rtrim($cssRelativeRoot, '\/');
        /** @var array|string[][] $cssFiles */
        $cssFiles = [];
        foreach (\scandir($directory, SCANDIR_SORT_NONE) as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }
            $csFilesFromPath = $this->getCsFilesFromPath($folder, $directory, $cssRelativeRoot, $level);
            foreach ($csFilesFromPath as $folderLevel => $cssFilesFromFolderLevel) {
                foreach ($cssFilesFromFolderLevel as $cssFileFromFolderLevel) {
                    $cssFiles[$folderLevel][] = $cssFileFromFolderLevel;
                }
            }
        }
        if ($level > 1) {
            return $cssFiles;
        }

        return $this->postProcessCssFiles($cssFiles);
    }

    protected function getCsFilesFromPath(string $folder, string $directory, string $cssRelativeRoot, int $level): array
    {
        $cssFiles = [];
        $folderPath = $directory . '/' . $folder;
        if (\is_dir($folderPath)) {
            if ($folder === 'ignore') {
                return [];
            }
            $anotherCssFiles = $this->scanForCssFiles(
                $folderPath,
                ($cssRelativeRoot !== '' ? ($cssRelativeRoot . '/') : '') . $folder,
                $level + 1
            );
            foreach ($anotherCssFiles as $iteratedLevel => $sameLevelAnotherCssFiles) {
                /** @var array $sameLevelAnotherCssFiles */
                foreach ($sameLevelAnotherCssFiles as $sameLevelAnotherCssFile) {
                    $cssFiles[$iteratedLevel][] = $sameLevelAnotherCssFile;
                }
            }
        } elseif (\is_file($folderPath) && \preg_match('~[.]css$~', $folder)) {
            $cssFiles[$level][] = ($cssRelativeRoot !== '' ? ($cssRelativeRoot . '/') : '') . $folder; // intentionally relative path
        }

        return $cssFiles;
    }

    protected function postProcessCssFiles(array $cssFiles): array
    {
        \krsort($cssFiles); // deeper means more generic and goes first
        $flattenedCss = [];
        foreach ($cssFiles as $sameLevelCssFiles) {
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $flattenedCss = \array_merge($flattenedCss, $sameLevelCssFiles); // deeper files can be overloaded by shallow ones
        }
        $flattenedCss = $this->removeMapFiles($flattenedCss);
        $flattenedCss = $this->filterUniqueFiles($flattenedCss);

        return $flattenedCss;
    }
}