<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Environment;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\String\StringTools;
use Granam\WebContentBuilder\HtmlDocument;
use Gt\Dom\Element;
use Mockery\MockInterface;

class HtmlHelperTest extends AbstractContentTest
{
    /**
     * @test
     * @dataProvider providePublicToLocalLinks
     * @param string $publicLink
     * @param string $expectedLocalLink
     */
    public function I_can_turn_public_link_to_local(string $publicLink, string $expectedLocalLink): void
    {
        self::assertSame($expectedLocalLink, HtmlHelper::turnToLocalLink($publicLink));
    }

    public function providePublicToLocalLinks(): array
    {
        return [
            ['https://www.drdplus.info', 'http://www.drdplus.loc'],
            ['https://hranicar.drdplus.info', 'http://hranicar.drdplus.loc'],
            ['https://bestiar.ppj.drdplus.info', 'http://bestiar.ppj.drdplus.loc'],
        ];
    }

    /**
     * @test
     */
    public function I_can_create_id_from_any_name(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        self::assertSame('kuala_lumpur', $htmlHelperClass::toId('Kuala lumpur'));
        self::assertSame('krizaly_s_mrkvi', $htmlHelperClass::toId('Křížaly s mrkví'));
    }

    /**
     * @test
     * @backupGlobals enabled
     * @dataProvider provideEnvironment
     * @param bool $forcedProduction
     * @param bool $onDev
     * @param bool $isCli
     * @param bool $onLocalhost
     * @param bool $expectingProduction
     */
    public function I_can_find_out_if_I_am_in_production(bool $forcedProduction, bool $onDev, bool $isCli, bool $onLocalhost, bool $expectingProduction): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        /** @var HtmlHelper $htmlHelper */
        $htmlHelper = new $htmlHelperClass(
            $this->getDirs(),
            $this->createEnvironment($onDev, $isCli, $onLocalhost),
            false,
            $forcedProduction,
            false
        );
        self::assertSame($expectingProduction, $htmlHelper->isInProduction());
    }

    public function provideEnvironment()
    {
        return [
            'production' => [false, false, false, false, true],
            'forced production on production' => [true, false, false, false, true],
            'dev' => [false, true, false, false, false],
            'forced production on dev' => [true, true, false, false, true],
            'cli' => [false, false, true, false, false],
            'forced production on cli' => [true, false, true, false, true],
            'localhost' => [false, false, false, true, false],
            'forced production on localhost' => [true, false, false, true, true],
            'dev cli' => [false, true, true, false, false],
            'forced production on dev cli' => [true, true, true, false, true],
            'dev localhost' => [false, true, true, false, false],
            'forced production on dev localhost' => [true, true, true, false, true],
        ];
    }

    /**
     * @param bool $onDev
     * @param bool $isCli
     * @param bool $onLocalhost
     * @return Environment|MockInterface
     */
    private function createEnvironment(bool $onDev, bool $isCli, bool $onLocalhost): Environment
    {
        $environment = $this->mockery(Environment::class);
        $environment->shouldReceive('isOnDevEnvironment')
            ->andReturn($onDev);
        $environment->shouldReceive('isCliRequest')
            ->andReturn($isCli);
        $environment->shouldReceive('isOnLocalhost')
            ->andReturn($onLocalhost);
        return $environment;
    }

    /**
     * @test
     */
    public function I_can_get_filtered_tables_from_content(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());

        $tablesWithIds = $htmlHelper->findTablesWithIds($this->getHtmlDocument());
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasTables()) {
            self::assertCount(0, $tablesWithIds);

            return;
        }
        self::assertGreaterThan(0, \count($tablesWithIds));
        self::assertEmpty($htmlHelper->findTablesWithIds($this->getHtmlDocument(), ['nonExistingTableId']));
        $someExpectedTableIds = $this->getTestsConfiguration()->getSomeExpectedTableIds();
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasTables()) {
            self::assertCount(0, $someExpectedTableIds, 'No tables expected');

            return;
        }
        self::assertGreaterThan(0, \count($someExpectedTableIds), 'Some tables expected');
        foreach ($someExpectedTableIds as $someExpectedTableId) {
            $lowerExpectedTableId = HtmlHelper::toId($someExpectedTableId);
            self::assertArrayHasKey($lowerExpectedTableId, $tablesWithIds, 'A table ID is missing');
            $expectedTable = $tablesWithIds[$lowerExpectedTableId];
            self::assertInstanceOf(Element::class, $expectedTable);
            self::assertNotEmpty($expectedTable->innerHTML, "Table of ID $someExpectedTableId is empty");
            // intentionally to snake case to test proper ID case conversion
            $someCasedExpectedTableId = HtmlHelper::toId($someExpectedTableId);
            $singleTable = $htmlHelper->findTablesWithIds($this->getHtmlDocument(), [$someCasedExpectedTableId]);
            self::assertCount(1, $singleTable, 'No table has been found by ID ' . $someCasedExpectedTableId);
            self::assertArrayHasKey($lowerExpectedTableId, $tablesWithIds, 'ID is expected to be lower-cased');
        }
    }

    /**
     * @test
     */
    public function Filtering_tables_by_id_does_not_crash_on_table_without_id(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());

        $allTables = $htmlHelper->findTablesWithIds(new HtmlDocument(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Just a test</title>
  <meta charset="utf-8">
</head>
<body>
  <table>No ID here</table>
</body>
</htm>
HTML
        ));
        self::assertCount(0, $allTables);
    }

    /**
     * @test
     */
    public function Same_table_ids_are_filtered_on_tables_only_mode(): void
    {
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasTables()) {
            self::assertCount(
                0,
                $this->getHtmlDocument()->getElementsByTagName('table'),
                'No tables with IDs expected according to tests config'
            );

            return;
        }
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());
        $someExpectedTableIds = $this->getTestsConfiguration()->getSomeExpectedTableIds();
        self::assertGreaterThan(0, \count($someExpectedTableIds), 'Some tables expected according to tests config');
        $tableId = \current($someExpectedTableIds);
        $tables = $htmlHelper->findTablesWithIds($this->getHtmlDocument(), [$tableId, $tableId]);
        self::assertCount(1, $tables);
    }

    /**
     * @test
     */
    public function It_will_not_add_anchor_into_anchor_with_id(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());
        $content = '<!DOCTYPE html>
<html lang="en"><body><a href="" id="someId">Foo</a></body></html>';
        $htmlDocument = new HtmlDocument($content);
        $htmlHelper->addAnchorsToIds($htmlDocument);
        self::assertSame($content, \trim($htmlDocument->saveHTML()));
    }

    /**
     * @test
     */
    public function Ids_are_turned_to_constant_like_diacritics_free_format(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());
        $originalId = 'Příliš # žluťoučký # kůň # úpěl # ďábelské # ódy';
        $htmlDocument = new HtmlDocument(<<<HTML
        <!DOCTYPE html>
<html lang="cs">
<head>
  <title>Just a test</title>
  <meta charset="utf-8">
</head>
<body>
  <div class="test" id="$originalId"></div>
</body>
</htm>
HTML
        );
        $htmlHelper->replaceDiacriticsFromIds($htmlDocument);
        $divs = $htmlDocument->getElementsByClassName('test');
        self::assertCount(1, $divs);
        $div = $divs[0];
        $id = $div->id;
        self::assertNotEmpty($id);
        $expectedId = StringTools::toConstantLikeValue($originalId);
        self::assertSame($expectedId, $id);
        $this->Original_id_is_accessible_without_change_via_data_attribute($div, $originalId);
        $this->Original_id_can_be_used_as_anchor_via_inner_invisible_element($div, $originalId);
    }

    private function Original_id_is_accessible_without_change_via_data_attribute(Element $elementWithId, string $expectedOriginalId): void
    {
        $fetchedOriginalId = $elementWithId->getAttribute(HtmlHelper::DATA_ORIGINAL_ID);
        self::assertNotEmpty($fetchedOriginalId);
        self::assertSame($expectedOriginalId, $fetchedOriginalId);
    }

    private function Original_id_can_be_used_as_anchor_via_inner_invisible_element(Element $elementWithId, string $expectedOriginalId): void
    {
        $invisibleIdElements = $elementWithId->getElementsByClassName(HtmlHelper::CLASS_INVISIBLE_ID);
        self::assertCount(1, $invisibleIdElements);
        $invisibleIdElement = $invisibleIdElements[0];
        $invisibleId = $invisibleIdElement->id;
        self::assertNotEmpty($invisibleId);
        self::assertSame(\str_replace('#', '_', $expectedOriginalId), $invisibleId);
    }

    /**
     * @test
     */
    public function I_can_turn_public_drd_plus_links_to_locals(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());
        $htmlDocument = new HtmlDocument(<<<HTML
        <!DOCTYPE html> 
<html lang="cs">
<head>
  <title>Just a test</title>
  <meta charset="utf-8">
</head>
<body>
  <a href="https://foo-bar.baz.drdplus.info" id="single_link">Sub-doména na DrD+ info</a>
  <a href="https://qux.drdplus.info" id="marked_as_local">Sub-doména na DrD+ info označená jako local</a>
</body>
</htm>
HTML
        );
        /** @var Element $localizedLink */
        $htmlHelper->markExternalLinksByClass($htmlDocument);
        $htmlHelper->makeExternalDrdPlusLinksLocal($htmlDocument);
        $localizedLink = $htmlDocument->getElementById('single_link');
        self::assertNotEmpty($localizedLink, 'No element found by ID single_link');
        self::assertSame('http://foo-bar.baz.drdplus.loc', $localizedLink->getAttribute('href'));
        /** @var Element $localizedLocalLikeLink */
        $localizedLocalLikeLink = $htmlDocument->getElementById('marked_as_local');
        self::assertNotEmpty($localizedLocalLikeLink, 'No element found by ID marked_as_local');
        self::assertSame('http://qux.drdplus.loc', $localizedLocalLikeLink->getAttribute('href'));
    }

    /**
     * @test
     * @dataProvider provideLinksToRemoteTables
     * @param array|string[] $links
     * @param string $expectedIframe
     * @param string $expectedIframeId
     */
    public function I_can_inject_iframes_with_remote_tables(array $links, string $expectedIframe, string $expectedIframeId): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());
        $implodedLinks = \implode("\n", $links);
        $htmlDocument = new HtmlDocument(<<<HTML
        <!DOCTYPE html>
<html lang="cs">
<head>
  <title>Just a test</title>
  <meta charset="utf-8">
</head>
<body>
  $implodedLinks
</body>
</htm>
HTML
        );
        $htmlHelper->markExternalLinksByClass($htmlDocument);
        $htmlHelper->injectIframesWithRemoteTables($htmlDocument);
        $iframes = $htmlDocument->getElementsByTagName('iframe');
        self::assertCount(1, $iframes, 'Single iframe (with tables preview) expected');
        $iframe = $iframes->current();
        self::assertSame(
            $expectedIframe,
            $iframe->getAttribute('src'),
            "Something is bad with iframe\n" . $iframe->outerHTML
        );
        self::assertSame($expectedIframeId, $iframe->id, 'Expected ID made from iframe target domain');
    }

    public function provideLinksToRemoteTables(): array
    {
        return [
            'pph.drdplus.info' => [
                [
                    '<a href="https://pph.drdplus.info/#tabulka_vzdalenosti">Odkaz na tabulku vzdálenosti</a>',
                    '<a href="https://pph.drdplus.info/#tabulka_vzdalenosti">Druhý odkaz na tabulku vzdálenosti</a>',
                    '<a href="https://pph.drdplus.info/#tabulka_casu">Odkaz na tabulku času</a>',
                    '<a href="https://pph.drdplus.info/#tabulka_vzdalenosti">Třetí na tabulku vzdálenosti</a>',
                ],
                'https://pph.drdplus.info/?tables=tabulka_vzdalenosti,tabulka_casu',
                'pph.drdplus.info',
            ],
            'theurg.drdplus.loc' => [
                ['<a href="http://theurg.drdplus.loc/#tabulka_formuli">Odkaz na lokálního theurga a jeho tabulku formulí</a>'],
                'http://theurg.drdplus.loc/?tables=tabulka_formuli',
                'theurg.drdplus.loc',
            ],
        ];
    }

    /**
     * @test
     */
    public function I_can_mark_external_links_by_class(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());
        $htmlDocument = new HtmlDocument(<<<HTML
        <!DOCTYPE html>
<html lang="cs">
<head>
  <title>Just a test</title>
  <meta charset="utf-8">
</head>
<body>
  <a id="link_without_anchor">Link without anchor</a>
</body>
</htm>
HTML
        );
        self::assertNull($htmlDocument->body->getAttribute(HtmlHelper::DATA_HAS_MARKED_EXTERNAL_URLS));
        $htmlHelper->markExternalLinksByClass($htmlDocument);
        self::assertSame('1', $htmlDocument->body->getAttribute(HtmlHelper::DATA_HAS_MARKED_EXTERNAL_URLS));
        /** @var Element $linkWithoutAnchor */
        $linkWithoutAnchor = $htmlDocument->getElementById('link_without_anchor');
        self::assertFalse($linkWithoutAnchor->classList->contains(HtmlHelper::CLASS_EXTERNAL_URL));
    }

    /**
     * @test
     */
    public function I_can_add_id_to_table(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());
        $htmlDocument = new HtmlDocument($content = <<<HTML
        <!DOCTYPE html>
<html lang="cs">
<table>
  <thead>
    <tr>
      <th>Tabulka <strong>noční míry</strong></th>
      <th>Tabulka denní míry</th>
    </tr>
  </thead>
</table>
</html>
HTML
        );
        self::assertSame($htmlDocument, $htmlHelper->addIdsToTables($htmlDocument));
        $firstTableWithId = $htmlDocument->getElementById($firstExpectedId = 'Tabulka noční míry');
        self::assertNotEmpty(
            $firstTableWithId,
            \sprintf('No element found by ID %s in content %s', $firstExpectedId, $htmlDocument->body->prop_get_outerHTML())
        );
        self::assertSame('th', $firstTableWithId->nodeName);
        $secondTableWithId = $htmlDocument->getElementById($secondExpectedId = 'Tabulka denní míry');
        self::assertNotEmpty(
            $secondTableWithId,
            \sprintf('No element found by ID %s in content %s', $secondExpectedId, $htmlDocument->body->prop_get_outerHTML())
        );
    }

    /**
     * @test
     */
    public function No_ids_are_added_to_table_with_id_in_head_cell(): void
    {
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelper = $htmlHelperClass::createFromGlobals($this->getDirs(), $this->getEnvironment());
        $htmlDocument = new HtmlDocument($content = <<<HTML
        <!DOCTYPE html>
<html lang="cs">
<table>
  <thead>
    <tr>
      <th>
        <span id="Tabulka pohybu">Tabulka pohybu</span> a k tomu <span id="Tabulka únavy z pohybu">Tabulka únavy z pohybu</span>
      </th>
    </tr>
  </thead>
</table>
</html>
HTML
        );
        self::assertSame($htmlDocument, $htmlHelper->addIdsToTables($htmlDocument));
        self::assertSame((new HtmlDocument($content))->saveHTML(), $htmlDocument->saveHTML());
    }
}