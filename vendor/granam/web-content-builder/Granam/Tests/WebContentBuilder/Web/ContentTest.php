<?php
namespace Granam\Tests\WebContentBuilder\Web;

use Granam\Tests\WebContentBuilder\Partials\AbstractContentTest;
use Granam\WebContentBuilder\Web\Body;
use Granam\WebContentBuilder\Web\Content;
use Granam\WebContentBuilder\Web\CssFiles;
use Granam\WebContentBuilder\Web\Head;
use Granam\WebContentBuilder\Web\JsFiles;
use Granam\WebContentBuilder\Web\WebFiles;

class ContentTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function I_can_get_content(): void
    {
        $content = $this->createContent();
        self::assertContains(
            preg_replace('~\s~', '', file_get_contents(__DIR__ . '/files/foo.html')),
            preg_replace('~\s~', '', $content->getValue())
        );
    }

    private function createContent(): Content
    {
        $htmlHelper = $this->createHtmlHelper();

        return new Content(
            $htmlHelper,
            new Head($htmlHelper, new CssFiles($this->getDirs(), false), new JsFiles($this->getDirs(), false)),
            new Body(new WebFiles(__DIR__ . '/files'))
        );
    }

    /**
     * @test
     */
    public function I_can_get_html_document(): void
    {
        $content = $this->createContent();
        $fooId = $content->getHtmlDocument()->getElementById('foo_id');
        self::assertNotEmpty($fooId);
    }
}
