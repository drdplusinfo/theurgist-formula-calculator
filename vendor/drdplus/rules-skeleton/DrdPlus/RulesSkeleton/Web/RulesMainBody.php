<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\Web\Body;
use Granam\WebContentBuilder\Web\WebFiles;

class RulesMainBody extends Body implements RulesBodyInterface
{
    /** @var array */
    private $contentValues;

    public function __construct(WebFiles $webFiles, array $contentValues)
    {
        parent::__construct($webFiles);
        $this->contentValues = $contentValues;
    }

    protected function fetchPhpFileContent(string $file): string
    {
        $content = new class($file, $this->contentValues)
        {
            /** @var string */
            private $file;
            /** @var array */
            private $contentValues;

            public function __construct(string $file, array $contentValues)
            {
                $this->file = $file;
                $this->contentValues = $contentValues;
            }

            public function fetchContent(): string
            {
                \extract($this->contentValues, \EXTR_SKIP);
                \ob_start();
                /** @noinspection PhpIncludeInspection */
                include $this->file;

                return \ob_get_clean();
            }
        };

        return $content->fetchContent();
    }

    public function postProcessDocument(HtmlDocument $htmlDocument): HtmlDocument
    {
        return $htmlDocument;
    }

}