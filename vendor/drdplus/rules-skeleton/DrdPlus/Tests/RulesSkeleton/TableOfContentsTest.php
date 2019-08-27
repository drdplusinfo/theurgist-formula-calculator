<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Gt\Dom\Element;

class TableOfContentsTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function I_can_navigate_to_chapter_with_same_name_as_table_of_contents_mentions(): void
    {
        /** @var Element $tableOfContents */
        $tableOfContents = $this->getHtmlDocument()->getElementById(HtmlHelper::toId(HtmlHelper::ID_TABLE_OF_CONTENTS));
        if (!$this->getTestsConfiguration()->hasTableOfContents()) {
            self::assertEmpty(
                $tableOfContents,
                'No items of table of contents expected due to tests configuration'
            );

            return;
        }
        self::assertNotEmpty(
            $tableOfContents,
            \sprintf("Missing table of contents with ID '%s' as tests configuration requests", HtmlHelper::toId(HtmlHelper::ID_TABLE_OF_CONTENTS))
        );
        $contents = $tableOfContents->getElementsByClassName('content');
        self::assertNotEmpty(
            $contents,
            'Expected some ".content" elements as items of a table of contents #tableOfContents' . $tableOfContents->outerHTML
        );
        foreach ($contents as $content) {
            $anchors = $content->getElementsByTagName('a');
            self::assertNotEmpty($anchors->count(), 'Expected some anchors in table of contents ' . $content->outerHTML);
            foreach ($anchors as $anchor) {
                $link = $anchor->getAttribute('href');
                if (\strpos($link, '#') !== 0) {
                    continue;
                }
                $name = $anchor->textContent;
                self::assertSame($link, '#' . HtmlHelper::toId($name));
            }
        }
    }
}