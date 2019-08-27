<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\PathProvider;
use DrdPlus\RulesSkeleton\RoutedDirs;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Mockery\MockInterface;

class RoutedDirsTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_use_it(): void
    {
        $dirsClass = static::getSutClass();
        /** @var RoutedDirs $routedDirs */
        $routedDirs = new $dirsClass('foo', $this->createPathProvider('\\bar'));
        self::assertSame('foo/web/bar', $routedDirs->getWebRoot());
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