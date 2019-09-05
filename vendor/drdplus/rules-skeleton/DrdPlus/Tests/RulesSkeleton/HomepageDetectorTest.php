<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HomepageDetector;
use DrdPlus\RulesSkeleton\PathProvider;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;
use Symfony\Component\Routing\Exception\ExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

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

    /**
     * @test
     * @dataProvider provideExceptionOfPathProvider
     * @param ExceptionInterface $exception
     */
    public function I_will_get_false_if_route_is_not_found(ExceptionInterface $exception)
    {
        $homepageDetectorWithNothing = new HomepageDetector($this->createBrokenPathProvider($exception));
        self::assertFalse($homepageDetectorWithNothing->isHomepageRequested());
    }

    public function provideExceptionOfPathProvider(): array
    {
        return [
            [new RouteNotFoundException()],
            [new ResourceNotFoundException()],
        ];
    }

    /**
     * @param ExceptionInterface $exception
     * @return PathProvider|MockInterface
     */
    private function createBrokenPathProvider(ExceptionInterface $exception): PathProvider
    {
        $pathProvider = $this->mockery(PathProvider::class);
        $pathProvider->shouldReceive('getPath')
            ->andThrow($exception);
        return $pathProvider;
    }
}
