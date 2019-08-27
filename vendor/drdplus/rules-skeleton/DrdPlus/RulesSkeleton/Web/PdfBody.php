<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Dirs;
use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\HtmlDocument;

class PdfBody extends StrictObject implements RulesBodyInterface
{
    /** @var Dirs */
    private $dirs;
    /** @var string|bool */
    private $pdfFile;

    public function __construct(Dirs $dirs)
    {
        $this->dirs = $dirs;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * @return string
     * @throws \DrdPlus\RulesSkeleton\Web\Exceptions\CanNotReadPdfFile
     */
    public function getValue(): string
    {
        $pdfFile = $this->getPdfFile();
        if (!$pdfFile) {
            return '';
        }

        $content = \file_get_contents($pdfFile);
        if ($content === false) {
            throw new Exceptions\CanNotReadPdfFile($pdfFile . ' can not be read');
        }

        return $content;
    }

    public function getPdfFile(): ?string
    {
        if ($this->pdfFile === null) {
            if (!\file_exists($this->dirs->getPdfRoot())) {
                $this->pdfFile = false;
            } else {
                $pdfFiles = \glob($this->dirs->getPdfRoot() . '/*.pdf');

                $this->pdfFile = $pdfFiles[0] ?? false;
            }
        }

        return $this->pdfFile ?: null;
    }

    public function postProcessDocument(HtmlDocument $htmlDocument): HtmlDocument
    {
        return $htmlDocument;
    }
}