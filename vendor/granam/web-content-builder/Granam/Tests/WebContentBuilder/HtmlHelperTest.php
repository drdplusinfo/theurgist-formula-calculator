<?php declare(strict_types=1);

namespace Granam\Tests\WebContentBuilder;

use Granam\WebContentBuilder\Exceptions\NameToCreateHtmlIdFromIsEmpty;
use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\HtmlHelper;
use Granam\Tests\WebContentBuilder\Partials\AbstractContentTest;
use Gt\Dom\Element;

class HtmlHelperTest extends AbstractContentTest
{
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
     */
    public function I_can_not_create_id_from_empty_name(): void
    {
        $this->expectException(NameToCreateHtmlIdFromIsEmpty::class);
        /** @var HtmlHelper $htmlHelperClass */
        $htmlHelperClass = static::getSutClass();
        $htmlHelperClass::toId('');
    }

    /**
     * @test
     * @dataProvider provideLinkWithHash
     * @param string $linkWithHash
     * @param null|string $includingUrlPattern
     * @param null|string $excludingUrlPattern
     * @param string $expectedLinkWithReplacedHash
     */
    public function I_can_replace_diacritics_from_link_hashes(
        string $linkWithHash,
        ?string $includingUrlPattern,
        ?string $excludingUrlPattern,
        string $expectedLinkWithReplacedHash
    ): void
    {
        $htmlHelper = $this->getHtmlHelper();
        $withReplacedHashes = $htmlHelper->replaceDiacriticsFromAnchorHashes(
            new HtmlDocument(<<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
<a id="just_some_link"
  href="{$linkWithHash}">Just some link</a>
</body>
</html>
HTML
            ),
            $includingUrlPattern,
            $excludingUrlPattern
        );
        /** @var Element $anchor */
        $anchor = $withReplacedHashes->getElementById('just_some_link');
        self::assertNotEmpty($anchor);
        self::assertSame($expectedLinkWithReplacedHash, $anchor->getAttribute('href'));
    }

    public function provideLinkWithHash(): array
    {
        return [
            'no hash at all' => ['https://example.com', null, null, 'https://example.com'],
            'with simple hash' => ['https://example.com#foo', null, null, 'https://example.com#foo'],
            'hash with diacritics' => ['https://example.com#fůů', null, null, 'https://example.com#fuu'],
            'hash with diacritics and included link' => ['https://example.com#fůů', '~example[.]com~', null, 'https://example.com#fuu'],
            'hash with diacritics and included but also excluded link' => ['https://example.com#fůů', '~example[.]com~', '~example~', 'https://example.com#fůů'],
            'hash with diacritics and missing match' => ['https://example.com#fůů', '~bar~', null, 'https://example.com#fůů'],
            'hash with diacritics and "including" hash' => ['https://example.com#fůů', '~fůů~', null, 'https://example.com#fůů'],
            'hash with diacritics and "excluding" hash' => ['https://example.com#fůů', null, '~fůů~', 'https://example.com#fuu'],
        ];
    }

    /**
     * @test
     * @dataProvider provideHtmlWithId
     * @param string $topId
     * @param string $htmlWithId
     * @param array $expectedAnchors
     * @param string $expectedHtmlWithId = null
     */
    public function I_can_wrap_id_by_anchor_to_it(string $topId, string $htmlWithId, array $expectedAnchors, string $expectedHtmlWithId = null): void
    {
        $htmlHelper = $this->getHtmlHelper();
        $withAnchorsOnIds = $htmlHelper->addAnchorsToIds(
            new HtmlDocument(<<<HTML
<!DOCTYPE html>
<html lang="cs">
<body>
{$htmlWithId}
</body>
</html>
HTML
            )
        );
        $elementWithId = $withAnchorsOnIds->getElementById(\html_entity_decode($topId));
        $anchors = $elementWithId->getElementsByTagName('a');
        self::assertSame(\count($expectedAnchors), \count($anchors), 'Expected different count of anchors inside ' . $withAnchorsOnIds->saveHTML());
        foreach ($expectedAnchors as $index => $expectedAnchor) {
            self::assertSame(
                $expectedAnchor,
                $anchors[$index]->outerHTML,
                'Expected different anchor from ' . $withAnchorsOnIds->saveHTML()
            );
        }
        if ($expectedHtmlWithId !== null) {
            self::assertSame($expectedHtmlWithId, $elementWithId->outerHTML, 'Expected different content');
        }
    }

    public function provideHtmlWithId(): array
    {
        return [
            'div with ID' => ['some_id', '<div id="some_id">Foo</div>', ['<a href="#some_id">Foo</a>']],
            'div with span with ID' => ['some_id', '<div><span id="some_id">Foo</span></div>', ['<a href="#some_id">Foo</a>']],
            'div with div with ID and div with span' => [
                'some_id',
                '<div><div id="some_id">Foo<span id="another_id">Bar</span></div></div>',
                ['<a href="#some_id">Foo<span id="another_id"><a href="#another_id">Bar</a></span></a>', '<a href="#another_id">Bar</a>'],
            ],
            'table with th with ID' => [
                'some_id',
                "<table><thead><th id='some_id'>Foo</th></thead></table>",
                ['<a href="#some_id">Foo</a>'],
            ],
            'div with ID containing encoded HTML entities' => [
                'Znalost&lt;divočiny&gt;',
                '<h6 id="Znalost&lt;divočiny&gt;">Znalost&lt;divočiny&gt;</h6>',
                ['<a href="#Znalost&lt;divo%C4%8Diny&gt;">Znalost&lt;divočiny&gt;</a>'],
            ],
            'heading with ID but marked as without link' => [
                'some_heading',
                '<h1 id="some_heading" class="' . HtmlHelper::CLASS_WITHOUT_ANCHOR_TO_ID . '">Some heading</h1>',
                [],
            ],
            'div with content in sub-div' => [
                'some_div',
                '<div id="some_div"><div>Some content in sub-div</div></div>',
                ['<a href="#some_div">Some content in sub-div</a>'],
                '<div id="some_div"><div><a href="#some_div">Some content in sub-div</a></div></div>',
            ],
            'div with content in sub-div but also text content after' => [
                'some_div_with_sub_sub_div',
                '<div id="some_div_with_sub_sub_div"><div><div>Some content in sub-sub-div</div></div>Some text after</div>',
                ['<a href="#some_div_with_sub_sub_div">Some content in sub-sub-div</a>'],
                <<<HTML
<div id="some_div_with_sub_sub_div">
<div><div><a href="#some_div_with_sub_sub_div">Some content in sub-sub-div</a></div></div>Some text after</div>
HTML
                ,
            ],
            'with white space content only and sub-div with text content' => [
                'with_white_space_content_and_filled_sub_dir',
                <<<HTML
<div id="with_white_space_content_and_filled_sub_dir">    

<div>Some content</div>

</div>
HTML
                ,
                ['<a href="#with_white_space_content_and_filled_sub_dir">Some content</a>'],
                <<<HTML
<div id="with_white_space_content_and_filled_sub_dir">    

<div><a href="#with_white_space_content_and_filled_sub_dir">Some content</a></div>

</div>
HTML
                ,
            ],
            'with single non-empty table head cell' => [
                'with_single_non_empty_table_head_cell',
                <<<HTML
<table id="with_single_non_empty_table_head_cell">
<thead>
<tr>
<th>Inside table head cell</th>
</tr>
</thead>
</table>
HTML
                ,
                ['<a href="#with_single_non_empty_table_head_cell">Inside table head cell</a>'],
            ],
            'with single non-empty table caption' => [
                'with_single_non_empty_table_caption',
                <<<HTML
<table id="with_single_non_empty_table_caption">
<caption>Inside caption</caption>
<thead>
<tr>
<th>Inside table head cell</th>
</tr>
</thead>
</table>
HTML
                ,
                ['<a href="#with_single_non_empty_table_caption">Inside caption</a>'],
            ],
            'with div and sub-div and table and sub-div' => [
                'with_div_and_sub_div_and_table_and_sub_div',
                <<<HTML
<div class="calculation" id="with_div_and_sub_div_and_table_and_sub_div">
  <div class="formula">Stabilizace = Zrč + stupeň archetypu + 2k6<span class="upper-index">+</span>:</div>
  <div>doba: <a href="http://pph.drdplus.loc/#tabulka_casu" target="_blank" class="external-url">2 kola (+6)</a>
  <table class="result content">
    <tbody>
      <tr>
        <td>méně než 5</td>
        <td>posun hranice ošetření</td>
      </tr>
      <tr>
        <td>5 až 9</td>
        <td>
          nestabilizoval <span class="note">(může zkusit sám znovu, pokud se mu <strong>změní podmínky</strong>, nebo někdo jiný)</span>
        </td>
      </tr>
      <tr>
        <td>10 až 14</td>
        <td>stabilizoval</td>
      </tr>
      <tr>
        <td>15+</td>
        <td>ošetřil</td>
      </tr>
    </tbody>
  </table>
</div>
HTML
                ,
                [
                    '<a href="#with_div_and_sub_div_and_table_and_sub_div">Stabilizace = Zrč + stupeň archetypu + 2k6<span class="upper-index">+</span>:</a>',
                    '<a href="http://pph.drdplus.loc/#tabulka_casu" target="_blank" class="external-url">2 kola (+6)</a>'
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideHtmlWithHeading
     * @param string $headingTag
     * @param string $htmlWithHeading
     * @param string|null $expectedHeading
     */
    public function I_can_add_id_to_heading(string $headingTag, string $htmlWithHeading, ?string $expectedHeading): void
    {
        $htmlHelper = $this->getHtmlHelper();
        $withAnchorsOnIds = $htmlHelper->addIdsToHeadings(new HtmlDocument(<<<HTML
<!DOCTYPE html>
<html lang="cs">
<body>
{$htmlWithHeading}
</body>
</html>
HTML
        ));
        $heading = $withAnchorsOnIds->getElementsByTagName($headingTag)[0];
        $id = $heading->getAttribute('id');
        self::assertSame($expectedHeading, $id, 'Expected different ID on heading ' . $heading->prop_get_outerHTML());
    }

    public function provideHtmlWithHeading(): array
    {
        return [
            'heading' => [
                'h1',
                '<h1>Some heading</h1>',
                'Some heading',
            ],
            'heading with class to suppress ID injection' => [
                'h2',
                '<h2 class="' . HtmlHelper::CLASS_HEADING_WITHOUT_ID . '">Another heading</h2>',
                null,
            ],
        ];
    }

    /**
     * @test
     */
    public function I_can_unify_ids_and_for_in_related_labels(): void
    {
        $htmlHelper = $this->getHtmlHelper();
        $unified = $htmlHelper->unifyIds(new HtmlDocument(<<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
<h1 class="text-to-left">Obsah</h1>
<div id="english">
  <label for="deadlyLiving">Human</label>
  <div id="deadlyLiving">Walking dude</div>
</div>
<div id="czech">
  <label for="Křivule pro srovnání">Nakřivo</label>
  <div id="Křivule pro srovnání">Srovnej si to</div>
</div>
</body>
</html>
HTML
        ));
        /** @var Element $english */
        $english = $unified->getElementById('english');
        self::assertNotEmpty($english);
        self::assertSame(
            <<<'HTML'
<label for="deadly_living" data-original-for="deadlyLiving">Human</label>
<div id="deadly_living" data-original-id="deadlyLiving">Walking dude<span id="deadlyLiving" class="invisible-id"></span>
</div>
HTML
            ,
            preg_replace('~(\s*\n\s*)+~', "\n", trim($english->prop_get_innerHTML()))
        );
        /** @var Element $czech */
        $czech = $unified->getElementById('czech');
        self::assertNotEmpty($czech);
        self::assertSame(
            <<<'HTML'
<label for="krivule_pro_srovnani" data-original-for="Křivule pro srovnání">Nakřivo</label>
<div id="krivule_pro_srovnani" data-original-id="Křivule pro srovnání">Srovnej si to<span id="Křivule pro srovnání" class="invisible-id"></span>
</div>
HTML
            ,
            preg_replace('~(\s*\n\s*)+~', "\n", trim($czech->prop_get_innerHTML()))
        );
    }

    /**
     * @test
     * @dataProvider provideHtmlWithIds
     * @param string $content
     * @param string|null $expectedId
     */
    public function I_can_get_first_id_in_any_element(string $content, ?string $expectedId): void
    {
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
$content
</body>
</html>
HTML;
        $htmlHelper = $this->getHtmlHelper();
        $element = (new HtmlDocument($html))->body->firstElementChild;
        self::assertSame($expectedId, $htmlHelper->getFirstIdFrom($element));
    }

    public function provideHtmlWithIds(): array
    {
        return [
            'first-class ID' => [
                '<div id="first">Foo
<div id="middle">

Bar
<span id="last">LAST</span>
</div>',
                'first',
            ],
            'in-the-middle ID' => [
                '<div>Foo
<div id="middle">

Bar
<span id="last">LAST</span>
</div>',
                'middle',
            ],
            'last ID' => ['<div>Foo
<div>

Bar
<span id="last">LAST</span>
</div>',
                'last',
            ],
            'none' => ['<div>Foo
<div>

Bar
<span>LAST</span>
</div>',
                null,
            ],
        ];
    }
}