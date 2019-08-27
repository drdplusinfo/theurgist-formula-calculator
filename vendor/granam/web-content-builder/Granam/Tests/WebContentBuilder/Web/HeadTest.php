<?php declare(strict_types=1);

namespace Granam\Tests\WebContentBuilder\Web;

use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\HtmlHelper;
use Granam\WebContentBuilder\Web\CssFiles;
use Granam\WebContentBuilder\Web\Head;
use Granam\Tests\WebContentBuilder\Partials\AbstractContentTest;
use Granam\WebContentBuilder\Web\JsFiles;

class HeadTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function I_can_set_own_page_title(): void
    {
        $title = 'foo BAR';
        /** @var Head $headClass */
        $headClass = static::getSutClass();
        /** @var Head $head */
        $head = new $headClass(
            $this->getHtmlHelper(),
            $this->getCssFiles(),
            $this->getJsFiles(),
            $title
        );
        self::assertSame($title, $head->getPageTitle());
        self::assertStringContainsString("<title>$title</title>", $head->getValue());
    }

    protected function getHtmlHelper(): HtmlHelper
    {
        return new HtmlHelper($this->getDirs());
    }

    protected function getCssFiles(): CssFiles
    {
        return new CssFiles($this->getDirs(), true);
    }

    protected function getJsFiles(): JsFiles
    {
        return new JsFiles($this->getDirs(), true);
    }

    /**
     * @test
     */
    public function I_can_set_favicon_url(): void
    {
        $faviconUrl = '/foo/bar.ico';
        /** @var Head $headClass */
        $headClass = static::getSutClass();
        /** @var Head $head */
        $head = new $headClass(
            $this->getHtmlHelper(),
            $this->getCssFiles(),
            $this->getJsFiles(),
            '',
            $faviconUrl
        );
        self::assertStringContainsString("<link rel=\"shortcut icon\" href=\"{$faviconUrl}\">", $head->getValue());
    }

    /**
     * @test
     */
    public function Head_contains_google_analytics_with_id(): void
    {
        /** @var Head $headClass */
        $headClass = static::getSutClass();
        /** @var Head $head */
        $head = new $headClass(
            $this->getHtmlHelper(),
            $this->getCssFiles(),
            $this->getJsFiles(),
            '',
            '',
            '123456'
        );
        $headString = $head->getValue();
        $htmlDocument = new HtmlDocument(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<title>Just some title</title>
{$headString}
</head>
</html>
HTML
        );
        /** @var \DOMElement $googleAnalytics */
        $googleAnalytics = $htmlDocument->getElementById('googleAnalyticsId');
        self::assertNotEmpty($googleAnalytics);
        $src = $googleAnalytics->getAttribute('src');
        self::assertNotEmpty($src);
        $parsed = \parse_url($src);
        $queryString = \urldecode($parsed['query'] ?? '');
        self::assertSame('id=123456', $queryString);
        self::assertSame('123456', $googleAnalytics->getAttribute('data-google-analytics-id'));
    }
}
