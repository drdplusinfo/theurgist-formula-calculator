<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class TracyTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Tracy_watch_it(): void
    {
        $content = $this->fetchContentFromLink($this->getTestsConfiguration()->getLocalUrl(), true)['content'];
        self::assertNotEmpty($content, 'Nothing has been fetched from ' . $this->getTestsConfiguration()->getLocalUrl());
        self::assertRegExp('~<script>\nTracy[.]Debug[.]init\([^\n]+\n</script>~', $content, 'Tracy debugger is not enabled');
    }
}