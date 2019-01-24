<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use Granam\WebContentBuilder\HtmlHelper;
use Granam\WebContentBuilder\Web\HeadInterface;

class TablesContent extends MainContent
{
    public function __construct(HtmlHelper $htmlHelper, HeadInterface $head, TablesBody $tablesBody)
    {
        parent::__construct($htmlHelper, $head, $tablesBody);
    }

}