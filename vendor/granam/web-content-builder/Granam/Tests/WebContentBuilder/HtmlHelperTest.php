<?php
declare(strict_types=1);

namespace Granam\Tests\WebContentBuilder;

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
     * @expectedException \Granam\WebContentBuilder\Exceptions\NameToCreateHtmlIdFromIsEmpty
     */
    public function I_can_not_create_id_from_empty_name(): void
    {
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
     */
    public function I_can_wrap_id_by_anchor_to_it(string $topId, string $htmlWithId, array $expectedAnchors): void
    {
        $htmlHelper = $this->getHtmlHelper();
        $withAnchorsOnIds = $htmlHelper->addAnchorsToIds(new HtmlDocument(<<<HTML
<!DOCTYPE html>
<html lang="cs">
<body>
{$htmlWithId}
</body>
</html>
HTML
        ));
        $elementWithId = $withAnchorsOnIds->getElementById(\html_entity_decode($topId));
        $anchors = $elementWithId->getElementsByTagName('a');
        self::assertSame(\count($expectedAnchors), \count($anchors), 'Expected different cout of anchors inside ' . $withAnchorsOnIds->saveHTML());
        foreach ($expectedAnchors as $index => $expectedAnchor) {
            self::assertSame(
                $expectedAnchor,
                $anchors[$index]->outerHTML,
                'Expected different anchor from ' . $withAnchorsOnIds->saveHTML()
            );
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
        ];
    }

    /**
     * @test
     */
    public function I_can_replace_diacritics_from_id(): void
    {
        $htmlHelper = $this->getHtmlHelper();
        $withIdsWithoutDiacritics = $htmlHelper->replaceDiacriticsFromIds(new HtmlDocument(<<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
<h1 class="text-to-left">Obsah</h1>
<div id="Břetislav"><span id="Svíčková s příšernou šlehačkou">Fůůj</span></div>
</body>
</html>
HTML
        ));
        /** @var Element $bretislav */
        $bretislav = $withIdsWithoutDiacritics->getElementById('bretislav');
        self::assertNotEmpty($bretislav);
        self::assertSame(
            <<<'HTML'
<span id="svickova_s_prisernou_slehackou" data-original-id="Svíčková s příšernou šlehačkou">Fůůj<span id="Svíčková s příšernou šlehačkou" class="invisible-id"></span></span>
<span id="Břetislav" class="invisible-id"></span>
HTML
            ,
            $bretislav->prop_get_innerHTML()
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