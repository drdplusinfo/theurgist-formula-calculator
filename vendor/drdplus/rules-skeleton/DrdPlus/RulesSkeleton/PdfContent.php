<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use DrdPlus\RulesSkeleton\Web\PdfBody;
use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\Web\Body;
use Granam\WebContentBuilder\Web\HtmlContentInterface;

class PdfContent extends StrictObject implements HtmlContentInterface
{
    /** @var Body */
    private $pdfBody;

    public function __construct(PdfBody $pdfBody)
    {
        $this->pdfBody = $pdfBody;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->pdfBody->getValue();
    }

    public function getHtmlDocument(): HtmlDocument
    {
        throw new Exceptions\PdfContentDoesNotSupportHtmlFormat('Can not convert PDF to HTML');
    }

}