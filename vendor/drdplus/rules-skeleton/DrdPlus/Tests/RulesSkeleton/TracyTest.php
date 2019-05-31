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
        $response = $this->fetchContentFromLink($this->getTestsConfiguration()->getLocalUrl(), self::WITH_BODY);
        self::assertSame(
            200,
            $response['responseHttpCode'],
            sprintf('Failed fetching content from %s, got %s', $this->getTestsConfiguration()->getLocalUrl(), print_r($response, true))
        );
        $content = $response['content'];
        self::assertNotEmpty($content, 'Nothing has been fetched from ' . $this->getTestsConfiguration()->getLocalUrl());
        self::assertRegExp('~<script>\nTracy[.]Debug[.]init\([^\n]+\n</script>~', $content, 'Tracy debugger is not enabled');
    }
}