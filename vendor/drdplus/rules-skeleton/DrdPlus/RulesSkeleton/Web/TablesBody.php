<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\HtmlDocument;

class TablesBody extends StrictObject implements RulesBodyInterface
{

    /** @var RulesMainBody */
    private $rulesMainBody;
    /** @var HtmlHelper */
    private $htmlHelper;
    /** @var Request */
    private $request;

    public function __construct(RulesMainBody $rulesMainBody, HtmlHelper $htmlHelper, Request $request)
    {
        $this->rulesMainBody = $rulesMainBody;
        $this->htmlHelper = $htmlHelper;
        $this->request = $request;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        $rawContent = $this->rulesMainBody->getValue();
        $rawContentDocument = new HtmlDocument($rawContent);
        $tables = $rawContentDocument->getElementsByTagName('table');
        $tablesContent = '';
        foreach ($tables as $table) {
            $tablesContent .= $table->outerHTML . "\n";
        }

        return <<<HTML
<div id="tables_only">
  $tablesContent
</div>
HTML;
    }

    public function postProcessDocument(HtmlDocument $htmlDocument): HtmlDocument
    {
        $tablesWithIds = $this->htmlHelper->findTablesWithIds($htmlDocument, $this->request->getRequestedTablesIds());
        foreach ($htmlDocument->body->children as $child) {
            $child->remove();
        }
        foreach ($tablesWithIds as $table) {
            $htmlDocument->body->appendChild($table);
        }

        return $htmlDocument;
    }
}