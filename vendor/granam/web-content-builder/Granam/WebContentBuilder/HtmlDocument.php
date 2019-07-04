<?php declare(strict_types=1);

namespace Granam\WebContentBuilder;

class HtmlDocument extends \Gt\Dom\HTMLDocument
{
    public function __construct($document = '', bool $formatOutput = true)
    {
        parent::__construct($document);
        $this->formatOutput = $formatOutput;
    }

    public function saveHTML(\DOMNode $node = null): string
    {
        $html = parent::saveHTML($node);

        return str_replace('</script><', "</script>\n<", $html);
    }
}