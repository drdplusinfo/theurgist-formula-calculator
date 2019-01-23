<?php
namespace Granam\WebContentBuilder\Web;

use Granam\Tests\WebContentBuilder\Partials\AbstractContentTest;

class ContentTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function I_can_get_content(): void
    {
        $content = $this->createContent();
        self::assertContains(
            \preg_replace('~\s~', '', \file_get_contents(__DIR__ . '/files/foo.html')),
            \preg_replace('~\s~', '', $content->getValue()
            )
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
