<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Cache;
use DrdPlus\RulesSkeleton\CurrentWebVersion;
use DrdPlus\RulesSkeleton\Exceptions\CanNotReadCachedContent;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\String\StringTools;
use Mockery\MockInterface;

class CacheTest extends AbstractContentTest
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
        $cacheClass = $this->getCacheClass();
        /** @var Cache $cache */
        $cache = new $cacheClass(
            $this->createCurrentWebVersionMock($version),
            $dirs,
            $this->createRequest(),
            $this->getContentIrrelevantParametersFilter(),
            $this->createGit(),
            Cache::NOT_IN_PRODUCTION,
            'foo'
        );
        self::assertSame($dirs->getCacheRoot() . '/' . $version, $cache->getCacheDir());
    }

    /**
     * @param string $version
     * @return CurrentWebVersion|MockInterface
     */
    private function createCurrentWebVersionMock(string $version): CurrentWebVersion
    {
        $currentWebVersions = $this->mockery($this->getCurrentWebVersionClass());
        $currentWebVersions->shouldReceive('getCurrentMinorVersion')
            ->andReturn($version);

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
        $cacheClass = $this->getCacheClass();
        /** @var Cache $cache */
        $cache = new $cacheClass(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $this->createRequest(),
            $this->getContentIrrelevantParametersFilter(),
            $this->createGit(),
            Cache::NOT_IN_PRODUCTION,
            \uniqid(__FUNCTION__, true)
        );
        self::assertFalse($cache->isCacheValid(), 'Nothing should be cached so far');
        $cache->cacheContent($content = 'foo of bar over baz!');
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
        $cacheClass = $this->getCacheClass();
        /** @var Cache $cache */
        $cache = new $cacheClass(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $this->createRequest([Request::CACHE => $cacheParameter]),
            $this->getContentIrrelevantParametersFilter(),
            $this->createGit(),
            Cache::NOT_IN_PRODUCTION,
            \uniqid(__FUNCTION__, true)
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
        $cacheClass = $this->getCacheClass();
        /** @var Cache $cacheWithoutTrial */
        $cacheWithoutTrial = new $cacheClass(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $this->createRequest(),
            $this->getServicesContainer()->getContentIrrelevantParametersFilter(),
            $this->createGit(),
            Cache::NOT_IN_PRODUCTION,
            $prefix = \uniqid(__FUNCTION__, true)
        );
        self::assertFalse($cacheWithoutTrial->isCacheValid(), 'Nothing should be cached so far');
        $cacheWithoutTrial->cacheContent($content = 'foo of bar over baz!');
        self::assertTrue($cacheWithoutTrial->isCacheValid(), 'Expected content to be cached now');
        self::assertSame($content, $cacheWithoutTrial->getCachedContent());
        /** @var Cache $cacheWithTrialRequest */
        $cacheWithTrialRequest = new $cacheClass(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $this->createRequest($cacheIrrelevantParameters),
            $this->getServicesContainer()->getContentIrrelevantParametersFilter(),
            $this->createGit(),
            Cache::NOT_IN_PRODUCTION,
            $prefix
        );
        self::assertTrue($cacheWithTrialRequest->isCacheValid(), 'Expected content to be already cached');
        self::assertSame($content, $cacheWithTrialRequest->getCachedContent());
    }

    public function provideCacheIrrelevantParameters(): array
    {
        return [
            Request::TRIAL => [[Request::TRIAL => 1]],
            Request::TRIAL_EXPIRED_AT => [[Request::TRIAL_EXPIRED_AT => \time()]],
            Request::TRIAL . ' and ' . Request::TRIAL_EXPIRED_AT => [[Request::TRIAL => 1, Request::TRIAL_EXPIRED_AT => \time()]],
        ];
    }
}