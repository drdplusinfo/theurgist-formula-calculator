<?php
namespace Granam\Tests\WebContentBuilder\Partials;

use Granam\Tests\Tools\TestWithMockery;
use Granam\WebContentBuilder\Web\AbstractPublicFiles;

class AbstractPublicFilesTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_remove_map_files(): void
    {
        $publicFiles = $this->createPublicFiles();
        $files = ['/foo.js.min', '/foo.js'];
        $withoutMapFiles = $publicFiles->removeMapFiles($files);
        self::assertSame($files, $withoutMapFiles);
        $filesWithMap = $files;
        $filesWithMap[] = '/foo.js.map';
        $withoutMapFiles = $publicFiles->removeMapFiles($filesWithMap);
        self::assertSame($files, $withoutMapFiles);
    }

    private function createPublicFiles(bool $preferMinified = false): AbstractPublicFiles
    {
        return new class($preferMinified) extends AbstractPublicFiles
        {
            public function removeMapFiles(array $files)
            {
                return parent::removeMapFiles($files);
            }

            public function filterUniqueFiles(array $files): array
            {
                return parent::filterUniqueFiles($files);
            }

            public function getIterator()
            {
            }

        };
    }

    /**
     * @test
     */
    public function I_can_filter_non_unique_files(): void
    {
        $mnifiedPublicFiles = $this->createPublicFiles(true /* prefer minified */);
        $files = ['/foo.min.js', '/foo.js', '/foo.js.map'];
        $withoutNonMinified = $mnifiedPublicFiles->filterUniqueFiles($files);
        self::assertSame(['/foo.min.js', '/foo.js.map'], $withoutNonMinified);
        $nonMinifiedPublicFiles = $this->createPublicFiles(/* prefer non-minified */);
        $withoutMinified = $nonMinifiedPublicFiles->filterUniqueFiles($files);
        self::assertSame(['/foo.js', '/foo.js.map'], $withoutMinified);
    }
}
