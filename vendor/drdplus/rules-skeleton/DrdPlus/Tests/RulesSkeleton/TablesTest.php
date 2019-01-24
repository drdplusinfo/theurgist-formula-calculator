<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\WebContentBuilder\HtmlDocument;

class TablesTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_get_tables_only(): void
    {
        $htmlDocument = $this->getHtmlDocument([Request::TABLES => '' /* all of them */]);
        $tables = $htmlDocument->body->getElementsByTagName('table');
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasTables()) {
            self::assertCount(0, $tables, 'No tables expected due to tests configuration');
        } else {
            self::assertGreaterThan(0, \count($tables), 'Expected some tables');
        }
        $this->There_is_no_other_content_than_tables($htmlDocument);
    }

    protected function There_is_no_other_content_than_tables(HtmlDocument $htmlDocument): void
    {
        $menuWrapper = $htmlDocument->getElementById(HtmlHelper::ID_MENU_WRAPPER);
        $menuWrapper->remove();
        foreach ($htmlDocument->getElementsByClassName(HtmlHelper::CLASS_INVISIBLE_ID) as $invisible) {
            $invisible->remove();
        }
        foreach ($htmlDocument->getElementsByClassName(HtmlHelper::CLASS_INVISIBLE) as $invisible) {
            $invisible->remove();
        }
        foreach ($htmlDocument->body->children as $child) {
            self::assertSame(
                'table',
                $child->tagName,
                'Expected only tables, got ' . $child->outerHTML
            );
        }
    }

    /**
     * @test
     */
    public function I_can_get_wanted_tables_from_content(): void
    {
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasTables()) {
            self::assertFalse(false, 'Disabled by tests configuration');

            return;
        }
        $implodedTables = \implode(',', $this->getTestsConfiguration()->getSomeExpectedTableIds());
        $htmlDocument = $this->getHtmlDocument([Request::TABLES => $implodedTables]);
        $tables = $htmlDocument->body->getElementsByTagName('table');
        self::assertNotEmpty(
            $tables,
            \sprintf(
                'No tables have been fetched from %s, when required IDs %s',
                $this->getTestsConfiguration()->getLocalUrl() . '?' . Request::TABLES . '=' . \urlencode($implodedTables),
                $implodedTables
            )
        );
        foreach ($this->getTestsConfiguration()->getSomeExpectedTableIds() as $tableId) {
            self::assertNotNull(
                $htmlDocument->getElementById(HtmlHelper::toId($tableId)), 'Missing table of ID ' . $tableId
            );
        }
        $this->There_is_no_other_content_than_tables($htmlDocument);
    }
}