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
            \sprintf(
                "Missing table of contents with ID '%s' as test configuration says by '%s'",
                HtmlHelper::toId(HtmlHelper::ID_TABLE_OF_CONTENTS),
                TestsConfiguration::HAS_TABLE_OF_CONTENTS
            )
        );
        $contents = $tableOfContents->getElementsByClassName('content');
        self::assertNotEmpty(
            $contents,
            sprintf(
                "Expected some elements with class '%s' as items of a '%s'",
                HtmlHelper::CLASS_CONTENT,
                $tableOfContents->outerHTML
            )
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

    /**
     * @test
     */
    public function I_can_navigate_to_all_tables_page_from_table_of_contents()
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
        $countOfLinksToTables = 0;
        $contents = $tableOfContents->getElementsByClassName('content');
        foreach ($contents as $content) {
            $anchors = $content->getElementsByTagName('a');
            foreach ($anchors as $anchor) {
                $link = $anchor->getAttribute('href');
                if ($link !== '/tabulky') {
                    continue;
                }
                $name = $anchor->textContent;
                self::assertSame('Tabulky', $name, 'Expected different name for link to tables-only page');
                $countOfLinksToTables++;
            }
        }
        if (!$this->getTestsConfiguration()->hasTables()) {
            self::assertSame(0, $countOfLinksToTables, 'No link to tables-only page expected as there are no tables at all');
        } else {
            self::assertSame(1, $countOfLinksToTables, "One link to tables-only page expected. Something like \n" . <<<HTML
<tr>
  <th><a href="/tabulky">Tabulky</a></th>
</tr>
HTML
            );
        }
    }
}