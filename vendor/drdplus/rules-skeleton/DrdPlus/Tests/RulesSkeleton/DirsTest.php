<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class DirsTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_use_it(): void
    {
        $dirsClass = static::getSutClass();
        /** @var Dirs $dirs */
        $dirs = new $dirsClass('foo');
        self::assertSame('foo', $dirs->getProjectRoot());
        self::assertSame('foo/vendor', $dirs->getVendorRoot());
        self::assertSame('foo/cache/' . \PHP_SAPI, $dirs->getCacheRoot());
        self::assertSame('foo/web', $dirs->getWebRoot());
    }

    /**
     * @test
     */
    public function I_will_get_current_skeleton_root_as_default_document_root(): void
    {
        $expectedDocumentRoot = \realpath($this->getProjectRoot());
        self::assertFileExists($expectedDocumentRoot, 'No real path found from document root ' . $this->getProjectRoot());
        self::assertSame($expectedDocumentRoot, \realpath($this->getDirs()->getProjectRoot()));
    }
}