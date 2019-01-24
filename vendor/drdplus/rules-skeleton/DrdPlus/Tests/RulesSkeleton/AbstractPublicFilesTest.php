<?php
namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\AbstractPublicFiles;
use Granam\Tests\Tools\TestWithMockery;

class AbstractPublicFilesTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_remove_map_files(): void
    {
        $files = ['/foo.js.min', '/foo.js'];
        $withoutMapFiles = $this->createWithPublicRemoveMapFiles(true)->removeMapFiles($files);
        self::assertSame($files, $withoutMapFiles);
        $filesWithMap = $files;
        $filesWithMap[] = '/foo.js.map';
        $withoutMapFiles = $this->createWithPublicRemoveMapFiles(false)->removeMapFiles($filesWithMap);
        self::assertSame($files, $withoutMapFiles);
    }

    public function createWithPublicRemoveMapFiles(bool $preferMinified): AbstractPublicFiles
    {
        return new class($preferMinified) extends AbstractPublicFiles
        {
            public function getIterator()
            {

            }

            public function removeMapFiles(array $files)
            {
                return parent::removeMapFiles($files);
            }

        };
    }

    /**
     * @test
     */
    public function I_can_filter_non_unique_files(): void
    {
        $files = ['/foo.min.js', '/foo.js', '/foo.js.map'];
        $withoutNonMinified = $this->createWithPublicFilterUniqueFiles(true)->filterUniqueFiles($files);
        self::assertSame(['/foo.min.js', '/foo.js.map'], $withoutNonMinified);
        $withoutMinified = $this->createWithPublicFilterUniqueFiles(false)->filterUniqueFiles($files);
        self::assertSame(['/foo.js', '/foo.js.map'], $withoutMinified);
    }

    public function createWithPublicFilterUniqueFiles(bool $preferMinified): AbstractPublicFiles
    {
        return new class($preferMinified) extends AbstractPublicFiles
        {
            public function getIterator()
            {

            }

            public function filterUniqueFiles(array $files): array
            {
                return parent::filterUniqueFiles($files);
            }

        };
    }

}
