<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\Web\BodyInterface;

interface RulesBodyInterface extends BodyInterface
{
    public function postProcessDocument(HtmlDocument $htmlDocument): HtmlDocument;
}