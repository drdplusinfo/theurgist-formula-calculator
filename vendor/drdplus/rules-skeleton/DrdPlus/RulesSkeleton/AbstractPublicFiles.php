<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

abstract class AbstractPublicFiles extends StrictObject implements \IteratorAggregate
{
    /**
     * @var bool
     */
    private $preferMinified;

    public function __construct(bool $preferMinified)
    {
        $this->preferMinified = $preferMinified;
    }

    protected function removeMapFiles(array $files)
    {
        return \array_filter($files, static function (string $file) {
            return !\preg_match('~[.]map$~', $file);
        });
    }

    protected function filterUniqueFiles(array $files): array
    {
        $nonMinifiedFiles = [];
        $minifiedFiles = [];
        foreach ($files as $file) {
            if (\preg_match('~[.]min[.][^\\/]+$~', $file)) {
                $minifiedFiles[$file] = $file;
            } else {
                $nonMinifiedFiles[$file] = $file;
            }
        }
        if (!$nonMinifiedFiles || !$minifiedFiles) {
            return $files;
        }
        $sameFiles = [];
        foreach ($nonMinifiedFiles as $nonMinifiedFile) {
            $minifiedFile = \preg_replace('~[.]([^.]+)$~', '.min.$1', $nonMinifiedFile);
            if (\array_key_exists($minifiedFile, $minifiedFiles)) {
                $sameFiles[$nonMinifiedFile] = $minifiedFile;
            }
        }
        if (!$sameFiles) {
            return $files;
        }
        $filesToRemove = $this->preferMinified
            ? \array_keys($sameFiles)
            : \array_values($sameFiles);
        foreach ($filesToRemove as $fileToRemove) {
            unset($files[\array_search($fileToRemove, $files, true)]);
        }

        return \array_values($files); // to reindex from zero
    }
}