<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HomepageDetector;
use DrdPlus\RulesSkeleton\PathProvider;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;

class HomepageDetectorTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_find_out_if_is_homepage_requested()
    {
        $homepageDetectorWithNothing = new HomepageDetector($this->createPathProvider(''));
        self::assertTrue($homepageDetectorWithNothing->isHomepageRequested());

        $homepageDetectorWithSlash = new HomepageDetector($this->createPathProvider('/'));
        self::assertTrue($homepageDetectorWithSlash->isHomepageRequested());

        $homepageDetectorWithSomeRoute = new HomepageDetector($this->createPathProvider('/foo'));
        self::assertFalse($homepageDetectorWithSomeRoute->isHomepageRequested());
    }

    /**
     * @param string $path
     * @return PathProvider|MockInterface
     */
    private function createPathProvider(string $path): PathProvider
    {
        $pathProvider = $this->mockery(PathProvider::class);
        $pathProvider->shouldReceive('getPath')
            ->andReturn($path);
        return $pathProvider;
    }
}
