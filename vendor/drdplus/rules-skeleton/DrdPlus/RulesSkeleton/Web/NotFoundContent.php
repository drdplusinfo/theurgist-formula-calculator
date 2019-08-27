<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\HtmlHelper;
use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\Web\HeadInterface;

class NotFoundContent extends MainContent
{
    public function __construct(HtmlHelper $htmlHelper, HeadInterface $head, NotFoundBody $body)
    {
        parent::__construct($htmlHelper, $head, $body);
    }

    protected function solveIds(HtmlDocument $htmlDocument): void
    {
        $this->htmlHelper->addIdsToTables($htmlDocument);
        $this->htmlHelper->unifyIds($htmlDocument);
        $this->htmlHelper->addAnchorsToIds($htmlDocument);
        $this->htmlHelper->replaceDiacriticsFromDrdPlusAnchorHashes($htmlDocument);
    }

}