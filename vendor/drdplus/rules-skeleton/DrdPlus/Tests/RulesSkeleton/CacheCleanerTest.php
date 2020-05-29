<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\CacheCleaner;
use DrdPlus\RulesSkeleton\Exceptions\CacheRootDirIsNotSafe;
use DrdPlus\RulesSkeleton\Exceptions\InvalidCacheRootDirSafetyCheck;
use Granam\Tests\Tools\TestWithMockery;

class CacheCleanerTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_clean_cache()
    {
        $cacheRootDir = sys_get_temp_dir() . '/' . uniqid(__FUNCTION__, true);
        mkdir($cacheRootDir);
        $tempFiles = [];
        for ($i = 3; $i > 1; $i--) {
            $tempFile = $cacheRootDir . '/' . uniqid(__FUNCTION__ . '-' . $i, true);
            touch($tempFile);
            $tempFiles[] = $tempFile;
        }
        sort($tempFiles);
        self::assertSame($tempFiles, $this->getFilesFromDir($cacheRootDir));

        $cacheCleaner = new CacheCleaner($cacheRootDir, sys_get_temp_dir());
        self::assertTrue($cacheCleaner->clearCache(), 'Clearing a cache should return true if no problem occurs');

        self::assertFileNotExists($cacheRootDir, "{$cacheRootDir} should be removed during cache clean");
    }

    private function getFilesFromDir(string $dir): array
    {
        $files = [];
        foreach (scandir($dir, \SCANDIR_SORT_NONE) as $file) {
            if ($file !== '.' && $file !== '..') {
                $files[] = $dir . '/' . $file;
            }
        }
        sort($files);
        return $files;
    }

    /**
     * @test
     */
    public function I_can_not_use_cache_cleaner_on_dir_not_matching_name_pattern()
    {
        $this->expectException(CacheRootDirIsNotSafe::class);
        $this->expectExceptionMessageMatches('~what~');
        new CacheCleaner('foo', 'what');
    }

    /**
     * @test
     */
    public function I_have_to_use_some_cache_dir_name_safety_check()
    {
        $this->expectException(InvalidCacheRootDirSafetyCheck::class);
        $this->expectExceptionMessageMatches('~empty string~');
        new CacheCleaner('foo', '');
    }

    /**
     * @test
     */
    public function I_can_clean_cache_even_if_cache_dir_exists_no_more()
    {
        $cacheRootDir = sys_get_temp_dir() . '/' . uniqid(__FUNCTION__, true);
        self::assertFileNotExists($cacheRootDir, 'Cache root dirt should not exist for this test');
        $cacheCleaner = new CacheCleaner($cacheRootDir, sys_get_temp_dir());
        self::assertTrue($cacheCleaner->clearCache(), 'Cache cleaner should return true if target cache dir does not exist');
    }
}
