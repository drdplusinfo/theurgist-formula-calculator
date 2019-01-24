<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\Web\BodyInterface;

class TablesBody extends StrictObject implements BodyInterface
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
        $tables = $this->htmlHelper->findTablesWithIds($rawContentDocument, $this->request->getRequestedTablesIds());
        $tablesContent = '';
        foreach ($tables as $table) {
            $tablesContent .= $table->outerHTML . "\n";
        }

        return $tablesContent;
    }
}