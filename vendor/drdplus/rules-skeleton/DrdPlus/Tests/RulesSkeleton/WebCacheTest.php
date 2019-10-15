<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Cache;
use DrdPlus\RulesSkeleton\CurrentWebVersion;
use DrdPlus\RulesSkeleton\Exceptions\CanNotReadCachedContent;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\WebCache;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\String\StringTools;
use Mockery\MockInterface;

class WebCacheTest extends AbstractContentTest
{
    /** @var string */
    protected $temporaryRootDir;

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->temporaryRootDir) {
            \exec('rm -fr ' . \escapeshellarg($this->temporaryRootDir));
        }
    }

    /**
     * @test
     * @dataProvider provideVersions
     * @param string $version
     */
    public function I_will_get_cache_dir_depending_on_current_version(string $version): void
    {
        // using temporary NON-existing dir to use more code
        $dirs = $this->createDirs($this->getTemporaryRootDir());
        $webCacheClass = $this->getWebCacheClass();
        $cacheSubDir = \uniqid(__FUNCTION__, true);
        /** @var WebCache $cache */
        $cache = new $webCacheClass(
            $this->createCurrentWebVersionMock($version),
            $dirs,
            $cacheSubDir,
            $this->getRequest(),
            $this->getContentIrrelevantRequestAliases(),
            $this->getContentIrrelevantParametersFilter(),
            $this->getGit(),
            Cache::NOT_IN_PRODUCTION
        );
        self::assertSame($dirs->getCacheRoot() . '/web/' . $cacheSubDir . '/' . $version, $cache->getCacheDir());
    }

    /**
     * @param string $currentMinorVersion
     * @return CurrentWebVersion|MockInterface
     */
    private function createCurrentWebVersionMock(string $currentMinorVersion): CurrentWebVersion
    {
        $currentWebVersions = $this->mockery($this->getCurrentWebVersionClass());
        $currentWebVersions->shouldReceive('getCurrentMinorVersion')
            ->andReturn($currentMinorVersion);

        return $currentWebVersions;
    }

    protected function getTemporaryRootDir(): string
    {
        if ($this->temporaryRootDir === null) {
            $this->temporaryRootDir = \sys_get_temp_dir() . '/' . \uniqid(StringTools::getClassBaseName(static::class), true);
        }

        return $this->temporaryRootDir;
    }

    public function provideVersions(): array
    {
        return [
            ['master'],
            ['9.8.7'],
        ];
    }

    /**
     * @test
     */
    public function I_will_get_cached_content_if_available(): void
    {
        $request = $this->mockery($this->getRequestClass());
        $request->makePartial();

        $webCacheClass = $this->getWebCacheClass();
        $cacheSubDir = \uniqid(__FUNCTION__, true);
        /** @var WebCache $cache */
        $cache = new $webCacheClass(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $cacheSubDir,
            $request,
            $this->getContentIrrelevantRequestAliases(),
            $this->getContentIrrelevantParametersFilter(),
            $this->getGit(),
            Cache::NOT_IN_PRODUCTION
        );
        self::assertFalse($cache->isCacheValid(), 'Nothing should be cached so far');
        $cache->cacheContent($content = 'foo of bar over baz!');
        self::assertTrue($cache->isCacheValid(), 'Expected content to be cached now');
        self::assertSame($content, $cache->getCachedContent());

        $request->allows('getPath')
            ->andReturn('/different-route');
        self::assertFalse($cache->isCacheValid(), 'Nothing should be cached due to different route');
        $cache->cacheContent($content);
        self::assertTrue($cache->isCacheValid(), 'Expected content to be cached now');
        self::assertSame($content, $cache->getCachedContent());
    }

    /**
     * @test
     * @dataProvider provideCacheParameter
     * @param string|null $cacheParameter
     * @param bool $expectedCacheValid
     */
    public function I_can_disable_cache(?string $cacheParameter, bool $expectedCacheValid): void
    {
        $webCacheClass = $this->getWebCacheClass();
        $cacheSubDir = \uniqid(__FUNCTION__, true);
        /** @var WebCache $cache */
        $cache = new $webCacheClass(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $cacheSubDir,
            $this->createRequest([Request::CACHE => $cacheParameter]),
            $this->getContentIrrelevantRequestAliases(),
            $this->getContentIrrelevantParametersFilter(),
            $this->getGit(),
            Cache::NOT_IN_PRODUCTION
        );
        self::assertFalse($cache->isCacheValid(), 'Nothing should be cached so far');
        $cache->cacheContent($content = 'pocked world');
        self::assertSame($expectedCacheValid, $cache->isCacheValid());
        try {
            $cache->getCachedContent();
        } catch (CanNotReadCachedContent $canNotReadCachedContent) {
            if ($expectedCacheValid) {
                throw $canNotReadCachedContent;
            }
            self::assertTrue(true);
        }
    }

    public function provideCacheParameter(): array
    {
        return [
            'null as cache parameter' => [null, true],
            'nonsense as cache parameter' => ['nonsense', true],
            'zero as cache parameter' => ['0', false],
            'disable as cache parameter' => ['disable', false],
            'disabled as cache parameter' => ['disabled', false],
        ];
    }

    /**
     * @test
     * @dataProvider provideCacheIrrelevantParameters
     * @param array $cacheIrrelevantParameters
     */
    public function I_will_get_same_cached_content_for_cache_irrelevant_parameters_as_for_full(array $cacheIrrelevantParameters): void
    {
        $contentIrrelevantParametersFilter = $this->getServicesContainer()->getContentIrrelevantParametersFilter();
        self::assertSame(
            [],
            $contentIrrelevantParametersFilter->removeContentIrrelevantParameters($cacheIrrelevantParameters),
            'Some parameters are not irrelevant for cache (and therefore page content)'
        );

        $webCacheClass = $this->getWebCacheClass();
        $cacheSubDir = \uniqid(__FUNCTION__, true);
        /** @var Cache $cacheWithoutTrial */
        $cacheWithoutTrial = new $webCacheClass(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $cacheSubDir,
            $this->getRequest(),
            $this->getContentIrrelevantRequestAliases(),
            $contentIrrelevantParametersFilter,
            $this->getGit(),
            Cache::NOT_IN_PRODUCTION
        );
        self::assertFalse($cacheWithoutTrial->isCacheValid(), 'Nothing should be cached so far');
        $cacheWithoutTrial->cacheContent($content = 'foo of bar over baz!');
        self::assertTrue($cacheWithoutTrial->isCacheValid(), 'Expected content to be cached now');
        self::assertSame($content, $cacheWithoutTrial->getCachedContent());

        /** @var Cache $cacheWithTrialRequest */
        $cacheWithTrialRequest = new $webCacheClass(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $cacheSubDir, // same sub-dir
            $this->createRequest($cacheIrrelevantParameters),
            $this->getContentIrrelevantRequestAliases(),
            $contentIrrelevantParametersFilter,
            $this->getGit(),
            Cache::NOT_IN_PRODUCTION
        );
        self::assertTrue($cacheWithTrialRequest->isCacheValid(), 'Expected content to be already cached');
        self::assertSame($content, $cacheWithTrialRequest->getCachedContent());
    }

    public function provideCacheIrrelevantParameters(): array
    {
        return [
            Request::TRIAL => [[Request::TRIAL => 1]],
        ];
    }
}