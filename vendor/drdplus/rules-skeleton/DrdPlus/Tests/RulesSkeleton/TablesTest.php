<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\WebContentBuilder\HtmlDocument;
use Gt\Dom\Element;
use Gt\Dom\NodeList;

class TablesTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_get_tables_only(): void
    {
        $htmlDocumentWithTablesOnly = $this->getHtmlDocument([Request::TABLES => '' /* all of them */]);
        /** @var NodeList|Element[] $tables */
        $tables = $htmlDocumentWithTablesOnly->getElementsByTagName('table');
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasTables()) {
            self::assertCount(0, $tables, 'No tables expected due to tests configuration');
            self::assertCount(0, $this->getTableIds(), 'No tables expected due to tests configuration');

            return;
        }
        $expectedTableIds = $this->getTableIds();
        $fetchedTableIds = $this->getElementsIds($tables);
        $missingIds = \array_diff($expectedTableIds, $fetchedTableIds);
        self::assertEmpty($missingIds, 'Some tables with IDs are missing: ' . \implode(',', $missingIds));
        $this->There_is_no_other_content_than_tables($htmlDocumentWithTablesOnly);
        $this->Expected_table_ids_are_present($fetchedTableIds);
    }

    protected function getTableIds(): array
    {
        static $tableIds;
        if ($tableIds === null) {
            $tableIds = $this->parseTableIds($this->getHtmlDocument());
            \sort($tableIds);
            $this->Expected_table_ids_are_present($tableIds);
        }

        return $tableIds;
    }

    private function getElementsIds(\Traversable $elements): array
    {
        return \array_map(
            function (Element $element): string {
                return $this->getHtmlHelper()->getFirstIdFrom($element);
            },
            $this->traversableToArray($elements)
        );
    }

    private function traversableToArray(\Traversable $iterable): array
    {
        $array = [];
        foreach ($iterable as $key => $value) {
            $array[$key] = $value;
        }

        return $array;
    }

    protected function Expected_table_ids_are_present(array $tableIds): void
    {
        $someExpectedTableIds = $this->getTestsConfiguration()->getSomeExpectedTableIds();
        $missingIds = \array_diff($someExpectedTableIds, $tableIds);
        self::assertEmpty($missingIds, 'Some expected table IDs are missing: ' . \implode(',', $missingIds));
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

    /**<
     * @test
     */
    public function I_can_get_wanted_tables_from_content(): void
    {
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasTables()) {
            self::assertFalse(false, 'Disabled by tests configuration');

            return;
        }
        $tableIds = $this->getTableIds();
        $implodedTables = \implode(',', $tableIds);
        $htmlDocument = $this->getHtmlDocument([Request::TABLES => $implodedTables]);
        $tables = $htmlDocument->body->getElementsByTagName('table');
        self::assertGreaterThan(
            0,
            $tables->count(),
            \sprintf(
                'No tables have been fetched from %s, when required IDs %s',
                $this->getTestsConfiguration()->getLocalUrl() . '?' . Request::TABLES . '=' . \urlencode($implodedTables),
                $implodedTables
            )
        );
        self::assertCount(count($tableIds), $tableIds, 'Expected same amount of tables as requested');
        self::assertSame(
            [],
            \array_diff($this->getTestsConfiguration()->getSomeExpectedTableIds(), $tableIds),
            'Some expected table IDs are missing'
        );
        $this->There_is_no_other_content_than_tables($htmlDocument);
    }
}