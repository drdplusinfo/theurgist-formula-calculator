<?php
declare(strict_types=1);

namespace Granam\WebContentBuilder\Web;

use Granam\Strict\Object\StrictObject;

class Body extends StrictObject implements BodyInterface
{
    /** @var WebFiles */
    private $webFiles;

    public function __construct(WebFiles $webFiles)
    {
        $this->webFiles = $webFiles;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        $content = '';
        foreach ($this->webFiles as $webFile) {
            if (\preg_match('~[.]php$~', $webFile)) {
                $content .= $this->fetchPhpFileContent($webFile);
            } elseif (\preg_match('~[.]md$~', $webFile)) {
                $content .= $this->fetchMarkdownFileContent($webFile);
            } else {
                $content .= $this->fetchFilePlainContent($webFile);
            }
        }

        return <<<HTML
<div class="main">
  <div class="background-image"></div>
  $content
</div>
HTML;
    }

    protected function fetchPhpFileContent(string $file): string
    {
        \ob_start();
        /** @noinspection PhpIncludeInspection */
        include $file;

        return \ob_get_clean();
    }

    protected function fetchMarkdownFileContent(string $file): string
    {
        return \Parsedown::instance()->parse($this->fetchFilePlainContent($file));
    }

    protected function fetchFilePlainContent(string $file): string
    {
        return \file_get_contents($file);
    }
}