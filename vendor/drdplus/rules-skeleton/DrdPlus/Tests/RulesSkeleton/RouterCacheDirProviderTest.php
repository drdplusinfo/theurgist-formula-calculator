<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\RouterCacheDirProvider;
use DrdPlus\RulesSkeleton\WebCache;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;
use Symfony\Component\Config\FileLocator;

class RouterCacheDirProviderTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_router_cache_dir()
    {
        $someTempFileName = sys_get_temp_dir() . '/' . uniqid(__FUNCTION__, true);
        $someTempFileContent = random_bytes(10);
        file_put_contents($someTempFileName, $someTempFileContent);
        $routerCacheDirProvider = new RouterCacheDirProvider(
            $this->createFileLocator('/baz.yml', $someTempFileName),
            '/baz.yml',
            $this->createWebCache('/foo/bar')
        );
        self::assertSame('/foo/bar/' . md5($someTempFileContent), $routerCacheDirProvider->getRouterCacheDir());
        unlink($someTempFileName);
    }

    /**
     * @param string $routesFile
     * @param string $routesFilePath
     * @return FileLocator|MockInterface
     */
    private function createFileLocator(string $routesFile, string $routesFilePath): FileLocator
    {
        $fileLocator = $this->mockery(FileLocator::class);
        $fileLocator->shouldReceive('locate')
            ->with($routesFile)
            ->andReturn($routesFilePath);
        return $fileLocator;
    }

    private function getRoutesFilePath(string $filePath): string
    {
        return $filePath;
    }

    /**
     * @param string $cacheDir
     * @return WebCache|MockInterface
     */
    private function createWebCache(string $cacheDir): WebCache
    {
        $webCache = $this->mockery(WebCache::class);
        $webCache->shouldReceive('getCacheDir')
            ->andReturn($cacheDir);
        return $webCache;
    }
}
