<?php declare(strict_types=1);

namespace Granam\WebContentBuilder\Web;

use Granam\Strict\Object\StrictObject;

/**
 * Gives files to serve on frontend (html, php or md)
 */
class WebFiles extends StrictObject implements \IteratorAggregate
{
    /** @var string */
    private $sourceDir;

    public function __construct(string $sourceDir)
    {
        $this->sourceDir = $sourceDir;
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->getSortedWebFileNames());
    }

    private function getSortedWebFileNames(): array
    {
        $htmlFileNames = $this->getUnsortedWebFileNames();

        $sorted = $this->sortFiles($htmlFileNames);

        return $this->extendRelativeToFullPath($sorted);
    }

    /**
     * @return array|string[]
     * @throws \Granam\WebContentBuilder\Web\Exceptions\UnknownWebFilesDir
     */
    private function getUnsortedWebFileNames(): array
    {
        if (!\is_dir($this->getWebFilesDir())) {
            throw new Exceptions\UnknownWebFilesDir("Can not read dir '{$this->getWebFilesDir()}' for web files");
        }

        return \array_filter(\scandir($this->getWebFilesDir(), \SCANDIR_SORT_NONE), function ($file) {
            return $file !== '.' && $file !== '..' && \preg_match('~\.(html|htm|php|md)$~', $file);
        });
    }

    protected function getWebFilesDir(): string
    {
        return $this->sourceDir;
    }

    /**
     * @param array|string[] $fileNames
     * @return array
     */
    private function sortFiles(array $fileNames): array
    {
        \usort($fileNames, function ($firstName, $secondName) {
            $firstNameParts = $this->parseNameParts($firstName);
            $secondNameParts = $this->parseNameParts($secondName);
            if (isset($firstNameParts['page'], $secondNameParts['page'])) {
                if ($firstNameParts['page'] !== $secondNameParts['page']) {
                    return $firstNameParts['page'] < $secondNameParts['page']
                        ? -1
                        : 1;
                }
                $firstNameColumn = $firstNameParts['column'] ?? '';
                $secondNameColumn = $secondNameParts['column'] ?? '';
                $columnComparison = \strcmp($firstNameColumn, $secondNameColumn);
                if ($columnComparison !== 0) {
                    return $columnComparison;
                }
                $firstNameOccurrence = $firstNameParts['occurrence'] ?? 0;
                $secondNameOccurrence = $secondNameParts['occurrence'] ?? 0;

                return $secondNameOccurrence - $firstNameOccurrence;
            }

            return 0;
        });

        return $fileNames;
    }

    /**
     * @param string $name
     * @return string[]|array
     */
    private function parseNameParts(string $name): array
    {
        \preg_match('~^(?<page>\d+)(?<column>\w+)?(?<occurrence>\d+)?\s+~', $name, $matches);

        return $matches;
    }

    /**
     * @param array $relativeFileNames
     * @return array|string[]
     */
    private function extendRelativeToFullPath(array $relativeFileNames): array
    {
        return \array_map(
            function ($htmlFile) {
                return $this->getWebFilesDir() . '/' . $htmlFile;
            },
            $relativeFileNames
        );
    }
}