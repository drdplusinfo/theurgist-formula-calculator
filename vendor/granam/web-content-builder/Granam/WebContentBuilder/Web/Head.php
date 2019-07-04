<?php declare(strict_types=1);

namespace Granam\WebContentBuilder\Web;

use Granam\WebContentBuilder\HtmlHelper;
use Granam\Strict\Object\StrictObject;

class Head extends StrictObject implements HeadInterface
{
    /** @var HtmlHelper */
    private $htmlHelper;
    /** @var CssFiles */
    private $cssFiles;
    /** @var JsFiles */
    private $jsFiles;
    /** @var string */
    private $pageTitle;
    /** @var string */
    private $faviconUrl;
    /** @var string|null */
    private $googleAnalyticsId;

    public function __construct(
        HtmlHelper $htmlHelper,
        CssFiles $cssFiles,
        JsFiles $jsFiles,
        string $pageTitle = '',
        string $faviconUrl = '',
        string $googleAnalyticsId = ''
    )
    {
        $this->htmlHelper = $htmlHelper;
        $this->cssFiles = $cssFiles;
        $this->jsFiles = $jsFiles;
        $this->pageTitle = $pageTitle;
        $this->faviconUrl = $faviconUrl;
        $this->googleAnalyticsId = $googleAnalyticsId;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        $headParts = [];
        if ($this->getPageTitle() !== '') {
            $headParts[] = "<title>{$this->getPageTitle()}</title>";
        }
        if ($this->faviconUrl !== '') {
            $headParts[] = "<link rel=\"shortcut icon\" href=\"{$this->faviconUrl}\">";
        }
        $headParts[] = '<meta http-equiv="Content-type" content="text/html;charset=UTF-8">';
        $headParts[] = '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">';
        $headParts[] = $this->getRenderedJsScripts();
        $headParts[] = $this->getRenderedCssFiles();

        return implode("\n", $headParts);
    }

    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    protected function getRenderedJsScripts(): string
    {
        $renderedJsFiles = [];
        if ($this->googleAnalyticsId) {
            $escapedGoogleAnalyticsId = \htmlspecialchars($this->googleAnalyticsId);
            $renderedJsFiles[] = <<<HTML
<script async src="https://www.googletagmanager.com/gtag/js?id={$escapedGoogleAnalyticsId}" id="googleAnalyticsId" data-google-analytics-id="{$escapedGoogleAnalyticsId}"></script>
HTML;
        }
        foreach ($this->getJsFiles() as $jsFile) {
            $renderedJsFiles[] = "<script type='text/javascript' src='/js/{$jsFile}'></script>";
        }

        return \implode("\n", $renderedJsFiles);
    }

    protected function getJsFiles(): JsFiles
    {
        return $this->jsFiles;
    }

    protected function getHtmlHelper(): HtmlHelper
    {
        return $this->htmlHelper;
    }

    protected function getRenderedCssFiles(): string
    {
        $renderedCssFiles = [];
        foreach ($this->getCssFiles() as $cssFile) {
            if (\strpos($cssFile, 'no-script.css') !== false) {
                $renderedCssFiles[] = <<<HTML
<noscript>
    <link rel="stylesheet" type="text/css" href="/css/{$cssFile}">
</noscript>
HTML;
            } else {
                $renderedCssFiles[] = <<<HTML
<link rel="stylesheet" type="text/css" href="/css/$cssFile">
HTML;
            }
        }

        return implode("\n", $renderedCssFiles);
    }

    protected function getCssFiles(): CssFiles
    {
        return $this->cssFiles;
    }
}