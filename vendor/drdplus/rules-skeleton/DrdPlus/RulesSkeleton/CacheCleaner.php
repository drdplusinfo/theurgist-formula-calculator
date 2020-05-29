<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class CacheCleaner extends StrictObject
{

    /**
     * @var string $cacheRootDir
     */
    private $cacheRootDir;

    /**
     * @param string $cacheRootDir
     * @param string $cacheDirHasToContain
     * @throws \DrdPlus\RulesSkeleton\Exceptions\CacheRootDirIsNotSafe
     */
    public function __construct(
        string $cacheRootDir,
        string $cacheDirHasToContain = DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR
    )
    {
        if ($cacheDirHasToContain === '') {
            throw new Exceptions\InvalidCacheRootDirSafetyCheck(
                'Some part of cache dir name to check has been expected, got empty string'
            );
        }
        if (strpos($cacheRootDir, $cacheDirHasToContain) === false) {
            throw new Exceptions\CacheRootDirIsNotSafe(
                sprintf(
                    "Expected a cache root dit containing '%s', but got '%s'. Is very dangerous to give invalid cache root dir as %s will delete it.",
                    $cacheDirHasToContain,
                    $cacheRootDir,
                    static::class
                )
            );
        }
        $this->cacheRootDir = $cacheRootDir;
    }

    public function clearCache(): bool
    {
        try {
            $recursiveDirectoryIterator = new \RecursiveDirectoryIterator($this->cacheRootDir, \RecursiveDirectoryIterator::SKIP_DOTS);
        } catch (\UnexpectedValueException $unexpectedValueException) {
            if (!file_exists($this->cacheRootDir)) {
                return true;
            }
            throw $unexpectedValueException;
        }
        $files = new \RecursiveIteratorIterator($recursiveDirectoryIterator, \RecursiveIteratorIterator::CHILD_FIRST);
        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            if ($file->isDir()) {
                if (!@rmdir($file->getPathname()) && file_exists($file->getPathname())) {
                    throw new Exceptions\CanNotDeleteCacheDir("Can not delete cache directory '{$file->getPathname()}'");
                }
            } elseif (!@unlink($file->getPathname()) && file_exists($file->getPathname())) {
                throw new Exceptions\CanNotDeleteCacheFile("Can not delete cache file '{$file->getPathname()}'");
            }
        }
        if (!@rmdir($this->cacheRootDir) && file_exists($this->cacheRootDir)) {
            throw new Exceptions\CanNotDeleteCacheDir("Can not delete cache root directory '{$this->cacheRootDir}'");
        }
        return true;
    }
}
